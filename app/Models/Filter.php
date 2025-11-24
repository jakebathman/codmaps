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
    ];

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
        $query->where('is_active', true);
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
