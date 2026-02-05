<?php

use App\Http\Controllers\ApiDataController;
use App\Http\Controllers\GithubAuthController;
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
    Route::view('overview', 'overview')->name('overview')->defaults('nav', 'Overview');

    Route::view('maps', 'maps')->name('maps')->defaults('nav', 'Maps');
    Route::view('filters', 'filters')->name('filters')->defaults('nav', 'Filters');
    Route::view('games', 'games')->name('games')->defaults('nav', 'Games');

    Route::view('codes', 'codes')->name('codes')->defaults('nav', 'Codes');
    Route::view('weapons', 'weapons')->name('weapons')->defaults('nav', 'Weapons');
    Route::view('binary', 'binary')->name('binary')->defaults('nav', 'Binary');
    Route::view('tools', 'tools')->name('tools')->defaults('nav', 'Tools');

    Route::view('decode', 'decode')->name('decode');
});

Route::view('data', 'data');

Route::prefix('api')
    ->middleware(['api', 'api.key'])
    ->group(function () {
        Route::get('data', ApiDataController::class);
    });

// Return radix livewire component
Route::get('radix', Radix::class)->name('radix');
