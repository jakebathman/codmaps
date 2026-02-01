<?php

namespace App\Livewire;

use App\Models\Attachment;
use App\Models\Weapon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Weapons extends Component
{
    public ?int $weaponId = null;

    public string $currentType = 'Assault Rifle';

    public array $skippedIds = [];

    public ?string $attachmentSearch = null;

    public ?array $expectedAttachmentCounts = [];

    public bool $showCounts = false;

    public string $activeTab = 'optic';

    public array $types = [
        'Assault Rifle',
        'Launcher',
        'LMG',
        'Marksman Rifle',
        'Melee',
        'Pistol',
        'Shotgun',
        'SMG',
        'Sniper Rifle',
        'Special',
    ];

    public array $attachmentTypes = [
        'optic',
        'muzzle',
        'barrel',
        'underbarrel',
        'magazine',
        'rear grip',
        'stock',
        'laser',
        'fire mods',
        'comb',
    ];

    public function mount()
    {
        $this->nextWeapon();
    }

    #[Computed]
    public function attachmentTypes()
    {
        return Attachment::all()
            ->groupBy('type')
            ->keys()
            ->toArray();
    }

    #[Computed]
    public function weapon()
    {
        return Weapon::where('id', $this->weaponId)
            ->with('attachments')
            ->first();
    }

    #[Computed]
    public function weaponsInType()
    {
        return Weapon::where('type', $this->currentType)
            ->with('attachments')
            ->get();
    }

    #[Computed]
    public function attachmentResults()
    {
        return Attachment::where('type', $this->activeTab)
            ->when($this->attachmentSearch, function ($query) {
                $query->where('name', 'like', '%' . $this->attachmentSearch . '%')
                    ->orWhere('label', 'like', '%' . $this->attachmentSearch . '%');
            })
            ->whereNotIn('id', $this->weapon?->attachments->pluck('id')->toArray() ?? [])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function needsExpectedCounts()
    {
        if ($this->showCounts === true) {
            return true;
        }

        return $this->weapon && is_null($this->weapon->expected_attachment_counts) || ! ($this->weapon->expected_attachment_counts['complete'] ?? false);
    }

    #[Computed]
    public function totalExpectedAttachments()
    {
        if (! $this->weapon || empty($this->expectedAttachmentCounts)) {
            return 0;
        }

        return collect($this->expectedAttachmentCounts)
            ->except(['complete', 'total'])
            ->sum(function ($count) {
                return is_numeric($count) ? (int) $count : 0;
            });
    }

    public function editCounts()
    {
        if (! $this->weapon) {
            return;
        }

        $this->expectedAttachmentCounts = $this->weapon->expected_attachment_counts?->toArray() ?? [];
        unset($this->needsExpectedCounts);
        $this->showCounts = true;
    }

    public function saveExpectedCounts()
    {
        if (! $this->weapon) {
            return;
        }

        foreach ($this->attachmentTypes as $t) {
            if (! isset($this->expectedAttachmentCounts[$t])) {
                $this->expectedAttachmentCounts[$t] = 0;
            }
        }

        $this->expectedAttachmentCounts['complete'] = true;
        $this->expectedAttachmentCounts['total'] = $this->totalExpectedAttachments();
        $this->weapon->expected_attachment_counts = $this->expectedAttachmentCounts;
        $this->weapon->save();

        $this->expectedAttachmentCounts = [];
        unset($this->weapon);
        $this->showCounts = false;
    }

    public function attachmentCountDisplay($type)
    {
        if (! $this->weapon) {
            return '—';
        }

        $attachmentsByType = $this->weapon->attachments->groupBy('type');

        if (! isset($attachmentsByType[$type])) {
            return 0;
        }

        return count($attachmentsByType[$type]);
    }

    public function countMatchesExpected($type): int
    {
        if (! $this->weapon) {
            return 0;
        }

        if (! isset($this->weapon->expected_attachment_counts[$type]) || $this->weapon->expected_attachment_counts[$type] === 0) {
            return -1;
        }

        if ($this->attachmentCountDisplay($type) > $this->weapon->expected_attachment_counts[$type]) {
            return 2;
        }

        if ($this->attachmentCountDisplay($type) == $this->weapon->expected_attachment_counts[$type]) {
            return 1;
        }

        return 0;
    }

    public function addAttachment($attachmentId)
    {
        if (! $this->weapon || ! $attachment = Attachment::find($attachmentId)) {
            return;
        }

        $this->weapon->attachments()->attach($attachment);
        unset($this->weapon);
    }

    public function removeAttachment($attachmentId)
    {
        if (! $this->weapon || ! $attachment = Attachment::find($attachmentId)) {
            return;
        }

        $this->weapon->attachments()->detach($attachment);
        unset($this->weapon);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setType($type)
    {
        $this->currentType = $type;
        $this->nextWeapon();
    }

    public function setWeapon($weaponId)
    {
        $this->weaponId = $weaponId;
        unset($this->weapon);

        $this->expectedAttachmentCounts = $this->weapon?->expected_attachment_counts?->toArray() ?? [];
        unset($this->needsExpectedCounts);

        $this->activeTab = 'optic';
    }

    public function nextWeapon()
    {
        $weaponId = Weapon::where('type', $this->currentType)
            ->whereNotIn('id', $this->skippedIds)
            ->first()?->id;

        if (! $weaponId) {
            // Next type in the array, after the current one
            $currentTypeIndex = array_search($this->currentType, $this->types);
            if ($currentTypeIndex !== false && isset($this->types[$currentTypeIndex + 1])) {
                $this->currentType = $this->types[$currentTypeIndex + 1];
                $weaponId = Weapon::where('type', $this->currentType)
                    ->whereNotIn('id', $this->skippedIds)
                    ->first()?->id;
            }
        }

        $this->setWeapon($weaponId);
    }

    public function skip()
    {
        $this->skippedIds[] = $this->weaponId;
        $this->nextWeapon();
    }

    public function render()
    {
        return view('livewire.weapons');
    }
}
