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
    public function attachmentResults()
    {
        return Attachment::when($this->attachmentSearch, function ($query) {
            $query->where('name', 'like', '%' . $this->attachmentSearch . '%')
                ->orWhere('label', 'like', '%' . $this->attachmentSearch . '%');
        })
            ->get();
    }

    public function addAttachment($attachmentId)
    {
        if (! $this->weapon || ! $attachment = Attachment::find($attachmentId)) {
            return;
        }

        $this->weapon->attachments()->attach($attachment);
        unset($this->weapon);
    }

    public function nextWeapon()
    {
        $this->weaponId = Weapon::where('type', $this->currentType)
            ->whereNotIn('id', $this->skippedIds)
            ->first()?->id;
        unset($this->weapon);
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
