<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $config = config('maps');
    return view('welcome', [
        'maps' => $config['maps'],
        'filters' => $config['filters'],
        'games' => $config['games'],
    ]);
});
