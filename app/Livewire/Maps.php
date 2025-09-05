<?php

namespace App\Livewire;

use Livewire\Component;

class Maps extends Component
{
    public $filterColors = [
        'search' => 'blue',
        'gunfight' => 'fuchsia',
        'small' => 'yellow',
        'medium' => 'orange',
        'large' => 'red',
        'prop' => 'teal',
        'shipment' => 'indigo',
    ];

    public $editing;

    public $form = [
        'name' => '',
        'game' => null,
        'filters' => [],
    ];

    public $filterInput = '';

    public function edit($mapName)
    {
        $map = collect(config('maps.maps'))
            ->first(fn ($m) => ($m['name'] ?? null) === $mapName);

        if (!$map) {
            return;
        }

        $this->editing = $mapName;
        $this->form['name'] = $map['name'] ?? '';
        // Pick first game if multiple; UI uses a single-select
        $this->form['game'] = ($map['games'][0] ?? null);
        $this->form['filters'] = $map['filters'] ?? [];
        $this->filterInput = '';
    }

    public function cancel()
    {
        $this->reset('editing', 'form', 'filterInput');
        $this->form = [
            'name' => '',
            'game' => null,
            'filters' => [],
        ];
    }

    public function save()
    {
        // Persistence is out of scope (data comes from config).
        // Close the editor for now.
        $this->cancel();
    }

    public function addFilter()
    {
        $value = trim((string) $this->filterInput);
        if ($value === '') {
            return;
        }

        // Only allow filters valid for the selected game
        $allowed = collect(config('maps.filters')[$this->form['game']] ?? []);
        if (!$allowed->contains($value)) {
            return;
        }

        if (!in_array($value, $this->form['filters'], true)) {
            $this->form['filters'][] = $value;
        }

        $this->filterInput = '';
    }

    public function removeFilter($value)
    {
        $this->form['filters'] = collect($this->form['filters'])
            ->reject(fn ($f) => $f === $value)
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.maps', [
            'maps' => collect(config('maps.maps'))->sortBy('name')->values(),
            'filters' => config('maps.filters'),
            'games' => config('maps.games'),
        ]);
    }
}
