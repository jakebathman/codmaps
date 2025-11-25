<?php

namespace App\Models;

use App\Models\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'is_active',
    ];

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public static function asArray(): array
    {
        return self::active()->get()->mapWithKeys(function ($game) {
            return [$game->key => ['name' => $game->name]];
        })->toArray();
    }
}
