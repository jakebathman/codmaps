<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Map as MapModel;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Maps extends Component
{
    use WithFileUploads;
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
        'image' => '',
    ];

    public $filterInput = '';
    public $imageUpload;
    public $defaultGame = 'bo7';

    public function edit($mapName)
    {
        // Prefer DB if present
        $row = MapModel::where('name', $mapName)->first();
        if ($row) {
            $this->editing = $row->name;
            $this->form['name'] = $row->name;
            $this->form['game'] = $row->game;
            $this->form['filters'] = $row->filters ?? [];
            $this->form['image'] = $row->image ?? '';
            $this->filterInput = '';
            return;
        }
    }

    public function create()
    {
        $this->editing = '(new)';
        $this->form = [
            'name' => '',
            'game' => $this->defaultGame,
            'filters' => [],
            'image' => '',
        ];
        $this->filterInput = '';
        $this->imageUpload = null;
    }

    public function cancel()
    {
        $this->reset('editing', 'form', 'filterInput', 'imageUpload');
        $this->form = [
            'name' => '',
            'game' => null,
            'filters' => [],
            'image' => '',
        ];
    }

    public function save()
    {
        // Identify existing row if editing an existing map
        $existing = $this->editing && $this->editing !== '(new)'
            ? MapModel::where('name', $this->editing)->first()
            : null;

        $validated = $this->validate([
            'form.name' => [
                'required', 'string', 'max:255',
                Rule::unique('maps', 'name')->ignore($existing?->id),
            ],
            'form.game' => 'required|string|max:255',
            'form.filters' => 'array',
            'form.filters.*' => 'string',
            'imageUpload' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);
        $data = $validated['form'];
        $upload = $validated['imageUpload'] ?? null;

        // Ensure unique list of filters with clean indexes
        $data['filters'] = array_values(array_unique($data['filters'] ?? []));

        // Require an image when creating a new map (unless a filename is already present from config)
        if (!$existing && !$upload && empty($this->form['image'])) {
            $this->addError('form.image', 'Image is required.');
            return;
        }

        // Find by the original name shown in the table (editing)
        $map = $existing;

        if (!$map) {
            $map = new MapModel();
        }

        $map->name = $data['name'];
        $map->game = $data['game'];
        $map->filters = $data['filters'];

        // Handle image upload
        if ($upload) {
            $slug = Str::slug($data['name']);
            $ext = strtolower($upload->getClientOriginalExtension() ?: 'jpg');
            $base = $slug;
            $filename = $base . '.' . $ext;
            $i = 1;
            while (Storage::disk('r2')->exists($filename) && $map->image !== $filename) {
                $filename = $base . '-' . $i . '.' . $ext;
                $i++;
            }
            $upload->storeAs('/', $filename, [
                'disk' => 'r2',
                'visibility' => 'public',
            ]);
            $map->image = $filename;
        } elseif (!$existing && !empty($this->form['image'])) {
            // When importing/saving a config-backed map without re-uploading
            $map->image = basename($this->form['image']);
        }

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
                    'image' => basename($m['image'] ?? ''),
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

        MapModel::query()->upsert($rows, ['name'], ['game', 'filters', 'image', 'updated_at']);

        // Ensure images exist on R2 by syncing from local if available
        foreach ($rows as $row) {
            $filename = $row['image'] ?? '';
            if ($filename === '') {
                continue;
            }
            $r2Path = $filename;
            if (!Storage::disk('r2')->exists($r2Path) && Storage::disk('public')->exists($r2Path)) {
                $stream = Storage::disk('public')->readStream($r2Path);
                if ($stream) {
                    Storage::disk('r2')->put($r2Path, $stream, ['visibility' => 'public']);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
            }
        }
    }

    public function render()
    {
        $maps = MapModel::all()
            ->map(function ($m) {
                return [
                    'name' => $m->name,
                    'game' => $m->game,
                    'filters' => $m->filters ?? [],
                    'image' => $m->image ?? '',
                    'image_url' => $m->image ? Storage::disk('r2')->url($m->image) : null,
                ];
            })
            ->sortBy('name')
            ->keyBy('name')
            ->toArray();
        return view('livewire.maps', [
            'maps' => $maps,
            'filters' => config('maps.filters'),
            'games' => config('maps.games'),
        ]);
    }

    public function imageUrl(?string $filename): ?string
    {
        $filename = basename((string) $filename);
        return $filename !== '' ? Storage::disk('r2')->url($filename) : null;
    }
}
