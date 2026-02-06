<?php

namespace App\Livewire;

use App\Models\Attachment;
use Livewire\Attributes\On;
use Livewire\Component;

class NewAttachmentModal extends Component
{
    public bool $open = false;
    public string $name = '';
    public string $label = '';
    public string $type = 'barrel';
    public bool $prestige = false;
    public string $notes = '';

    public array $types = [
        'barrel',
        'comb',
        'fire mods',
        'laser',
        'magazine',
        'muzzle',
        'optic',
        'rear grip',
        'stock',
        'underbarrel',
    ];

    #[On('open-new-attachment-modal')]
    public function openModal()
    {
        $this->open = true;
        $this->name = '';
        $this->label = '';
        $this->type = 'barrel';
        $this->prestige = false;
        $this->notes = '';
    }

    public function closeModal()
    {
        $this->open = false;
        $this->name = '';
        $this->label = '';
        $this->type = 'barrel';
        $this->prestige = false;
        $this->notes = '';
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', $this->types),
            'prestige' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $attachment = Attachment::create([
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'prestige' => $this->prestige,
            'notes' => $this->notes ?: null,
        ]);

        $this->dispatch('attachment-created', attachmentId: $attachment->id);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.new-attachment-modal');
    }
}
