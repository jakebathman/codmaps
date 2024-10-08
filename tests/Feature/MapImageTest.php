<?php

use Illuminate\Support\Facades\Storage;

it('checks if the file exists in the storage', function () {
    $expect = [];
    $actual = [];
    foreach (config('maps') as $map) {
        $expect[$map['name']] = true;
        $actual[$map['name']] = Storage::disk('public')->exists('images/' . $map['image']);
    }

    expect($actual)->toEqual($expect);
});
