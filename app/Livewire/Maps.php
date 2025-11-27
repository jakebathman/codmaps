<?php

namespace App\Livewire;

use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use App\Models\Map as MapModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    public $editing = null;

    public $search = '';

    public $selectedGame;

    public $form = [
        'name' => '',
        'game' => null,
        'filters' => [],
        'image' => '',
        'is_active' => false,
    ];

    public $filterInput = '';
    public $imageUpload;
    public $defaultGame = 'bo7';
    public $gameFilter = null;
    public $filterByMissingImage = false;
    public $filterByInactive = false;

    public function edit($mapId)
    {
        $row = MapModel::where('id', $mapId)->first();
        if ($row) {
            $this->editing = $row->id;
            $this->form['name'] = $row->name;
            $this->form['game'] = $row->game;
            $this->form['filters'] = $row->filters ?? [];
            $this->form['image'] = $row->image ?? '';
            $this->filterInput = '';
            $this->dispatch('maps:scroll-top');
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
        $this->dispatch('maps:scroll-top');
    }

    public function cancel()
    {
        $map = $this->editing && $this->editing !== '(new)'
        ? MapModel::where('id', $this->editing)->first()
        : null;

        $this->reset('editing', 'form', 'filterInput', 'imageUpload');
        $this->form = [
            'name' => '',
            'game' => null,
            'filters' => [],
            'image' => '',
        ];

        if ($map) {
            $this->dispatch('maps:scroll-to', key: md5($map->id));
        }
    }

    public function save()
    {
        // Identify existing row if editing an existing map
        $existing = $this->editing && $this->editing !== '(new)'
        ? MapModel::where('id', $this->editing)->first()
        : null;

        $allMapsForGame = MapModel::where('game', $this->form['game'])
            ->when($existing, fn($q) => $q->whereNot('id', $existing->id))
            ->pluck('name')
            ->map(fn($n) => (string) strtolower($n))
            ->toArray();

        $validated = $this->validate([
            'form.name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($allMapsForGame) {
                    if (in_array(strtolower($value), $allMapsForGame, true)) {
                        $fail('This is a duplicate of another map for this game.');
                    }
                },
            ],
            'form.game' => 'required|string|max:255',
            'form.filters' => 'array',
            'form.filters.*' => 'string',
            'form.is_active' => 'boolean',
            'imageUpload' => 'nullable|file|mimes:jpg,jpeg,png',
        ]);
        $data = $validated['form'];
        $upload = $validated['imageUpload'] ?? null;

        // Ensure unique list of filters with clean indexes
        $data['filters'] = array_values(array_unique($data['filters'] ?? []));

        // Require an image when creating a new map (unless a filename is already present from config)
        if (! $existing && ! $upload && empty($this->form['image'])) {
            // $this->addError('form.image', 'Image is required.');
            // return;
        }

        // Find by the original name shown in the table (editing)
        $map = $existing;

        if (! $map) {
            $map = new MapModel();
        }

        $map->name = $data['name'];
        $map->game = $data['game'];
        $map->filters = $data['filters'];
        $map->is_active = $data['is_active'] ?? false;
        dd($data);

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
        } elseif (! $existing && ! empty($this->form['image'])) {
            // When importing/saving a config-backed map without re-uploading
            $map->image = basename($this->form['image']);
        }

        try {
            $map->save();
        } catch (\Throwable $e) {
            $this->addError('form.name', 'Failed to save: ' . $e->getMessage());
            return;
        }

        $this->dispatch('maps:scroll-to', key: md5($map->id));
        $this->cancel();
    }

    public function addFilter()
    {
        dd(__CLASS__ . '@' . __FUNCTION__ . '#' . __LINE__);
        $value = trim((string) $this->filterInput);
        if ($value === '') {
            return;
        }

        // Only allow filters valid for the selected game
        $allowed = collect((Filter::asArray())[$this->form['game']] ?? []);
        if (! $allowed->contains($value)) {
            return;
        }

        if (! in_array($value, $this->form['filters'], true)) {
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

        $allowed = collect(Filter::asArray()[$this->form['game']] ?? []);
        if (! $allowed->contains($value)) {
            return;
        }

        if (! in_array($value, $this->form['filters'], true)) {
            $this->form['filters'][] = $value;
        }
    }

    public function removeFilter($value)
    {
        $this->form['filters'] = collect($this->form['filters'])
            ->reject(fn($f) => $f === $value)
            ->values()
            ->all();
    }

    public function delete()
    {
        if (! $this->editing || $this->editing === '(new)') {
            return;
        }

        $map = MapModel::where('id', $this->editing)->first();
        if ($map) {
            $map->delete();
        }

        $this->cancel();
    }

    #[On('maps:import-from-config')]
    public function importFromConfig()
    {
        $now = now();
        $rows = collect(Game::asArray())
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
            ->filter(fn($r) => $r['name'] !== '')
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
            if (! Storage::disk('r2')->exists($r2Path) && Storage::disk('public')->exists($r2Path)) {
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

    public function suggestions()
    {
        $filters = Filter::asArray();
        $allowed = collect($filters[$this->form['game']] ?? []);
        $selected = collect($this->form['filters'] ?? []);
        return $allowed->reject(fn($f) => $selected->contains($f))->values();
    }

    public function render()
    {
        $search = trim((string) $this->search);

        $maps = MapModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';
                $query->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('game', 'like', $like);
                });
            })
            ->when($this->gameFilter !== null, function ($query) {
                $query->where('game', $this->gameFilter);
            })
            ->when($this->filterByMissingImage, function ($query) {
                $query->whereNull('image')->orWhere('image', '');
            })
            ->when($this->filterByInactive, function ($query) {
                $query->where('is_active', false);
            })
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->name,
                    'game' => $m->game,
                    'filters' => $m->filters ?? [],
                    'image' => $m->image ?? '',
                    'image_url' => $m->image ? Storage::disk('r2')->url($m->image) : null,
                    'is_active' => $m->is_active,
                ];
            })
            ->sortBy('name')
            ->keyBy('id')
            ->toArray();

        return view('livewire.maps', [
            'maps' => $maps,
            'filters' => Filter::asArray(),
            'games' => Game::asArray(),
        ]);
    }

    public function filterByGame(?string $game = null): void
    {
        $game = $game !== null ? trim($game) : null;

        $validGames = array_keys(Game::asArray() ?? []);
        if ($game !== null && ! in_array($game, $validGames, true)) {
            return;
        }

        $this->gameFilter = $this->gameFilter === $game ? null : $game;
    }

    public function setFilterByMissingImage(): void
    {
        $this->filterByMissingImage = ! $this->filterByMissingImage;
    }

    public function setFilterByInactive(): void
    {
        $this->filterByInactive = ! $this->filterByInactive;
    }

    public function imageUrl(?string $filename): ?string
    {
        $filename = basename((string) $filename);
        return $filename !== '' ? Storage::disk('r2')->url($filename) : null;
    }
}
