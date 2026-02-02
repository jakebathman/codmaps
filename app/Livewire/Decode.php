<?php

namespace App\Livewire;

use App\Models\Weapon;
use App\Support\BuildCode;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Decode extends Component
{
    public ?string $codeInput = null;

    #[Computed]
    public function buildCode(): ?BuildCode
    {
        return new BuildCode(trim(strtoupper($this->codeInput ?? '')));
    }

    #[Computed]
    public function weapon(): ?Weapon
    {
        return $this->buildCode?->weapon;
    }

    #[Computed]
    public function attachments(): ?Collection
    {
        return $this->buildCode?->attachments ?? collect();
    }

    public function render()
    {
        return view('livewire.decode');
    }
}
