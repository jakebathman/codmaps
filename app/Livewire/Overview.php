<?php

namespace App\Livewire;

use App\Models\Attachment;
use App\Models\Weapon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Overview extends Component
{
    public ?string $filterAttachmentType = null;

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

    #[Computed]
    public function attachments()
    {
        $query = Attachment::orderBy('type')->orderBy('name');

        if ($this->filterAttachmentType) {
            $query = $query->where('type', $this->filterAttachmentType);
        }

        return $query->get();
    }

    #[Computed]
    public function typesWithCounts()
    {
        return Attachment::all()
            ->groupBy('type')
            ->map(function ($group) {
                $empty = $group->whereNull('code_base34')->count();
                $filled = $group->whereNotNull('code_base34')->count();
                $total = $group->count();
                return [
                    'empty' => $empty,
                    'filled' => $filled,
                    'total' => $total,
                    'percent_complete' => $total > 0 ? round(($filled / $total) * 100, 2) : 0,
                ];
            })
            ->toArray();
    }

    public function filterAttachments($type)
    {
        if ($this->filterAttachmentType === $type) {
            $this->filterAttachmentType = null;
            return;
        }

        $this->filterAttachmentType = $type;
    }
}
