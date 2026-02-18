<?php

namespace App\Livewire;

use App\Models\Attachment;
use App\Models\AttachmentID;
use App\Models\Weapon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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

    public string $cloneCodeInput = '';

    public ?string $cloneError = null;

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
        $search = trim($this->attachmentSearch ?? '');

        return Attachment::where('type', $this->activeTab)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('label', 'like', '%' . $search . '%')
                        ->orWhere('code_base34', 'like', '%' . str_replace('-', '', $search) . '%');
                });
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

    #[On('weapon-created')]
    public function weaponCreated($weaponId)
    {
        // Optionally switch to the newly created weapon
        $weapon = Weapon::find($weaponId);
        if ($weapon) {
            $this->currentType = $weapon->type;
            $this->setWeapon($weaponId);
        }
    }

    #[On('attachment-cloned')]
    public function attachmentCloned()
    {
        // Close the clone dropdown by re-rendering the component (since the dropdown is controlled by Livewire state)
        $this->cloneCodeInput = '';
        $this->cloneError = null;
    }

    public function cloneAttachment($attachmentId, $newCode)
    {
        $this->cloneError = null;
        $code = $this->attachmentsCode();
        $oldAttachment = Attachment::find($attachmentId);
        if (! $this->weapon || ! $oldAttachment || ! $code) {
            return;
        }

        // Make sure this weapon doesn't have an attachment with the same code already
        if ($this->weapon->attachments()->where('code_base34', $code)->exists()) {
            // flash message an error
            $this->cloneError = 'This weapon already has an attachment with the same code.';
            return;
        }

        // If the attachment being cloned doesn't have a code_base34
        // simply update that record instead of making a new one
        if ($oldAttachment->code_base34 === null) {
            $oldAttachment->code_base34 = $code;
            $oldAttachment->code_base10 = $this->base34ToBase10($code);
            $oldAttachment->save();

            // No need to update the pivot table since the attachment ID is the same and already attached to the weapon
        } else {
            $clone = $oldAttachment->replicate();
            $clone->code_base34 = $code;
            $clone->code_base10 = $this->base34ToBase10($code);
            $clone->notes = null;
            $clone->prestige = false;
            $clone->save();

            // Unlink the old attachment from the weapon and link the new one
            $this->weapon->attachments()->detach($oldAttachment);
            $this->weapon->attachments()->attach($clone);
        }

        unset($this->weapon);

        // Event to close the clone dropdown
        $this->dispatch('attachment-cloned');
    }

    public function render()
    {
        return view('livewire.weapons');
    }

    #[Computed]
    public function attachmentsCode()
    {
        // Code without weapon prefix and hyphens, and removing the final 1
        $code = strtoupper($this->cloneCodeInput);
        if (preg_match('/^(?:(\w\d\d)-)?([\-1-9A-NP-Z]+)1$/', $code, $matches)) {
            return trim(str_replace('-', '', $matches[2]));
        }

        return null;
    }

    #[Computed]
    public function attachmentsCodeIdExists()
    {
        if (! $this->attachmentsCode()) {
            return null;
        }

        return AttachmentID::where('base_34', $this->attachmentsCode())->exists();
    }

    public function base34ToBase10($encoded): string
    {
        // Cast to string first, before any operations
        $encoded = (string) trim(strtoupper($encoded ?? ''));

        $alphabet = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $base = '34';

        $result = '0';
        $length = strlen($encoded);

        for ($i = 0; $i < $length; $i++) {
            $char = $encoded[$i];
            $value = strpos($alphabet, $char);

            if ($value === false) {
                throw new InvalidArgumentException("Invalid character in base34 string: {$char}");
            }

            // result = result * base + value
            $result = bcadd(bcmul($result, $base, 0), (string) $value, 0);
        }

        return $result;
    }

}
