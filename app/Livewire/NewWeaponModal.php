<?php

namespace App\Livewire;

use App\Models\Weapon;
use Livewire\Attributes\On;
use Livewire\Component;

class NewWeaponModal extends Component
{
    public bool $open = false;
    public string $name = '';
    public string $codePrefix = '';
    public string $type = 'Assault Rifle';

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

    #[On('open-new-weapon-modal')]
    public function openModal()
    {
        $this->open = true;
        $this->name = '';
        $this->codePrefix = '';
        $this->type = 'Assault Rifle';
    }

    public function closeModal()
    {
        $this->open = false;
        $this->name = '';
        $this->codePrefix = '';
        $this->type = 'Assault Rifle';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'codePrefix' => ['required', 'string', 'regex:/^[A-Z]\d{2}$/', 'unique:weapons,code_prefix'],
            'type' => 'required|in:' . implode(',', $this->types),
        ]);

        $weapon = Weapon::create([
            'name' => $this->name,
            'code_prefix' => $this->codePrefix,
            'type' => $this->type,
        ]);

        $this->dispatch('weapon-created', weaponId: $weapon->id);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.new-weapon-modal');
    }
}
