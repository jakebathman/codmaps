<?php

namespace App\Livewire;

use App\Models\Weapon;
use Livewire\Component;

class Overview extends Component
{
    public array $weaponTypes = [
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

    public function render()
    {
        return view('livewire.overview', [
            'weapons' => Weapon::with('attachments')->orderBy('type')->orderBy('name')->get(),
        ]);
    }
}
