<?php

use App\Livewire\Maps;
use App\Models\Map;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $config = config('maps');
    $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));
    return view('home', [
        'maps' => Map::orderBy('name')->get(),
        'filters' => $config['filters'],
        'games' => $config['games'],
        'commitHash' => $hash,
    ]);
});

Route::get('maps', Maps::class)->name('maps');

Route::view('data', 'data');
