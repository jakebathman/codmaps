<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;

class Games extends Component
{
    public $games = [];

    public $gameId = null;
    public $name = '';
    public $key = '';
    public $isActive = false;

    public $showForm = false;

    public function mount()
    {
        $this->games = Game::all();
    }

    public function render()
    {
        return view('livewire.games');
    }

    public function closeForm()
    {
        $this->reset('gameId', 'name', 'key', 'isActive');
        $this->showForm = false;
    }

    public function edit($id)
    {
        $game = Game::find($id);
        if ($game) {
            $this->gameId = $game->id;
            $this->name = $game->name;
            $this->key = $game->key;
            $this->isActive = $game->is_active;

            $this->showForm = true;
        }
    }

    public function save()
    {
        // validate input
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:10',
            'isActive' => 'boolean',
        ]);

        $updateData = [
            'name' => $data['name'],
            'key' => $data['key'],
            'is_active' => $data['isActive'],
        ];

        if ($this->gameId) {
            // Update an existing game
            $game = Game::find($this->gameId);
            if ($game) {
                $game->update($updateData);
            }
        } else {
            // Create new game record
            $game = Game::create($updateData);
        }

        // Refresh games list and clear form
        $this->games = Game::all();
        $this->reset('gameId', 'name', 'key', 'isActive');
        $this->showForm = false;
    }
}
