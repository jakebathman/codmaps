<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Map as MapModel;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

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
        // Prefer DB if present
        $row = MapModel::where('name', $mapName)->first();
        if ($row) {
            $this->editing = $row->name;
            $this->form['name'] = $row->name;
            $this->form['game'] = $row->game;
            $this->form['filters'] = $row->filters ?? [];
            $this->filterInput = '';
            return;
        }

        // Fallback to config
        $map = collect(config('maps.maps'))
            ->first(fn ($m) => ($m['name'] ?? null) === $mapName);
        if ($map) {
            $this->editing = $mapName;
            $this->form['name'] = $map['name'] ?? '';
            // Pick first game if multiple; UI uses a single-select
            $this->form['game'] = ($map['games'][0] ?? null);
            $this->form['filters'] = $map['filters'] ?? [];
            $this->filterInput = '';
        }
    }

    public function create()
    {
        $this->editing = '(new)';
        $this->form = [
            'name' => '',
            'game' => null,
            'filters' => [],
        ];
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
        // Identify existing row if editing an existing map
        $existing = $this->editing && $this->editing !== '(new)'
            ? MapModel::where('name', $this->editing)->first()
            : null;

        $data = $this->validate([
            'form.name' => [
                'required', 'string', 'max:255',
                Rule::unique('maps', 'name')->ignore($existing?->id),
            ],
            'form.game' => 'required|string|max:255',
            'form.filters' => 'array',
            'form.filters.*' => 'string',
        ])['form'];

        // Ensure unique list of filters with clean indexes
        $data['filters'] = array_values(array_unique($data['filters'] ?? []));

        // Find by the original name shown in the table (editing)
        $map = $existing;

        if (!$map) {
            $map = new MapModel();
        }

        $map->name = $data['name'];
        $map->game = $data['game'];
        $map->filters = $data['filters'];
        try {
            $map->save();
        } catch (\Throwable $e) {
            $this->addError('form.name', 'Failed to save: ' . $e->getMessage());
            return;
        }

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

    public function addFilterValue($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return;
        }

        $allowed = collect(config('maps.filters')[$this->form['game']] ?? []);
        if (!$allowed->contains($value)) {
            return;
        }

        if (!in_array($value, $this->form['filters'], true)) {
            $this->form['filters'][] = $value;
        }
    }

    public function removeFilter($value)
    {
        $this->form['filters'] = collect($this->form['filters'])
            ->reject(fn ($f) => $f === $value)
            ->values()
            ->all();
    }

    public function delete()
    {
        if (!$this->editing || $this->editing === '(new)') {
            return;
        }

        $map = MapModel::where('name', $this->editing)->first();
        if ($map) {
            $map->delete();
        }

        $this->cancel();
    }

    #[On('maps:import-from-config')]
    public function importFromConfig()
    {
        $now = now();
        $rows = collect(config('maps.maps'))
            ->map(function ($m) use ($now) {
                return [
                    'name' => $m['name'] ?? '',
                    'game' => $m['games'][0] ?? null,
                    'filters' => json_encode($m['filters'] ?? []),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })
            ->filter(fn ($r) => $r['name'] !== '')
            ->values()
            ->all();

        if (empty($rows)) {
            return;
        }

        MapModel::query()->upsert($rows, ['name'], ['game', 'filters', 'updated_at']);
    }

    public function render()
    {
        $maps = MapModel::all()->keyBy('name')->toArray();
        return view('livewire.maps', [
            'maps' => $maps,
            'filters' => config('maps.filters'),
            'games' => config('maps.games'),
        ]);
    }
}
