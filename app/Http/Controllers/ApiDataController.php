<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Http\Request;

class ApiDataController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(
            [
                'filters' => Filter::with('game')->get(),
                'games' => Game::all(),
                'maps' => Map::all(),
            ]
        );
    }
}
