<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $config = config('maps');
    $hash = trim(exec('git log --pretty="%h" -n1 HEAD'));
    return view('welcome', [
        'maps' => $config['maps'],
        'filters' => $config['filters'],
        'games' => $config['games'],
        'commitHash' => $hash,
    ]);
});
