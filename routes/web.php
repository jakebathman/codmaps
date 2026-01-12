<?php

use App\Http\Controllers\ApiDataController;
use App\Http\Controllers\GithubAuthController;
use App\Livewire\Filters;
use App\Livewire\Games;
use App\Livewire\Maps;
use App\Livewire\Radix;
use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));

    return view('home', [
        'maps' => Map::active()->orderBy('name')->get(),
        'filters' => Filter::asArray(),
        'games' => Game::asArray(),
        'commitHash' => $hash,
    ]);
})
    ->name('home');

Route::get('auth/github', [GithubAuthController::class, 'redirect'])->name('github.redirect');
Route::get('auth/github/callback', [GithubAuthController::class, 'callback'])->name('github.callback');
Route::post('logout', [GithubAuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['github.auth']], function () {
    Route::get('maps', Maps::class)->middleware('github.auth')->name('maps');
    Route::get('filters', Filters::class)->name('filters');
    Route::get('games', Games::class)->name('games');
    Route::view('codes', 'codes')->name('codes');
});

Route::view('data', 'data');

Route::prefix('api')
    ->middleware(['api', 'api.key'])
    ->group(function () {
        Route::get('data', ApiDataController::class);
    });

// Return radix livewire component
Route::get('radix', Radix::class)->name('radix');

