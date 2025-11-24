<?php

use App\Http\Controllers\ApiDataController;
use App\Livewire\Filters;
use App\Livewire\Games;
use App\Livewire\Maps;
use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));

    $allFilters = Filter::active()->with('game')->get();

    $filters = $allFilters->mapWithKeys(function ($filter) use ($allFilters) {
        return [
            $filter->game->key => $allFilters->where('game_id', $filter->game->id)->pluck('name'),
        ];
    })
        ->toArray();

    return view('home', [
        'maps' => Map::orderBy('name')->get(),
        'filters' => $filters,
        'games' => Game::active()->get()->mapWithKeys(function ($game) {
            return [$game->key => ['name' => $game->name]];
        })->toArray(),
        'commitHash' => $hash,
    ]);
})
    ->name('home');

Route::get('maps', Maps::class)->name('maps');
Route::get('filters', Filters::class)->name('filters');
Route::get('games', Games::class)->name('games');

Route::view('data', 'data');

Route::prefix('api')
    ->middleware(['api', 'api.key'])
    ->group(function () {
        Route::get('data', ApiDataController::class);
    });
