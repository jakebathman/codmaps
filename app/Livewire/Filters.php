<?php

namespace App\Livewire;

use App\Models\Filter;
use App\Models\Game;
use Livewire\Component;

class Filters extends Component
{
    public $filters = [];
    public $games = [];

    public $filterId = null;
    public $gameId = null;
    public $name = '';
    public $isActive = false;

    public $showForm = false;

    public $filterColors = [
        'search' => 'blue',
        'gunfight' => 'fuchsia',
        'small' => 'yellow',
        'medium' => 'orange',
        'large' => 'red',
        'prop' => 'teal',
        'shipment' => 'indigo',
    ];

    public function mount()
    {
        $this->filters = Filter::with('game')->get()->sortBy('game.name');
        $this->games = Game::all();
    }

    public function render()
    {
        return view('livewire.filters');
    }

    public function closeForm()
    {
        $this->reset('filterId', 'gameId', 'name', 'isActive');
        $this->showForm = false;
    }

    public function edit($id)
    {
        $filter = Filter::find($id);
        if ($filter) {
            $this->filterId = $filter->id;
            $this->gameId = $filter->game_id;
            $this->name = $filter->name;
            $this->isActive = $filter->is_active;

            $this->showForm = true;
        }
    }

    public function save()
    {
        // validate input
        $data = $this->validate([
            'gameId' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'isActive' => 'boolean',
        ]);

        $updateData = [
            'game_id' => $data['gameId'],
            'name' => $data['name'],
            'is_active' => $data['isActive'],
        ];

        if ($this->filterId) {
            // Update an existing filter
            $filter = Filter::find($this->filterId);
            if ($filter) {
                $filter->update($updateData);
            }
        } else {
            // Create new filter record
            $filter = Filter::create($updateData);
        }

        // Refresh filters list and clear form
        $this->filters = Filter::all();
        $this->reset('filterId', 'gameId', 'name', 'isActive');
        $this->showForm = false;
    }

    public function sortItem($itemId, $position, $k)
    {
        $oldPositionItem = Filter::all()->firstWhere('id', $itemId)->toArray();
        $oldPosition = $oldPositionItem['position'] > $k ? $k : $oldPositionItem['position'] - 1;
        $newGroupPosition = max(0, $oldPosition - ($k - $position) - 1);
        $filters = Filter::where('game_id', $oldPositionItem['game_id'])->get();

        $filters = array_filter($filters->toArray(), fn($f) => $f['id'] !== $oldPositionItem['id']);
        array_splice($filters, $newGroupPosition, 0, [$oldPositionItem]);
        foreach ($filters as $index => $filter) {
            Filter::where('game_id', $oldPositionItem['game_id'])->where('filters.id', $filter['id'])->update(['position' => $index + 1]);
        }

        $this->resetFilters();
    }

    public function resetFilters()
    {
        $this->filters = Filter::with('game')->get()->sortBy('game.name');
    }
}
