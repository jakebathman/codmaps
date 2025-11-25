<?php

namespace App\Models;

use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'is_active',
        'position',
    ];

    // boot to add default ordering by position
    protected static function booted(): void
    {
        static::addGlobalScope('sortByGameAndPosition', function ($builder) {
            $table = $builder->getModel()->getTable();

            $builder
                ->leftJoin('games', 'games.id', '=', "$table.game_id")
                ->orderBy('games.name')
                ->orderBy("$table.position")
                ->select("$table.*"); // avoid column collisions
        });

        static::saving(function ($filter) {
            if (is_null($filter->position)) {
                $maxPosition = self::where('game_id', $filter->game_id)->max('position');
                $filter->position = is_null($maxPosition) ? 1 : $maxPosition + 1;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('filters.is_active', true);
    }

    public static function asArray(): array
    {
        $allFilters = self::active()->with('game')->get();
        return Filter::active()->with('game')->get()->mapWithKeys(function ($filter) use ($allFilters) {
            return [
                $filter->game->key => $allFilters->where('game_id', $filter->game->id)->pluck('name'),
            ];
        })
            ->toArray();

    }
}
