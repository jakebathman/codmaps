<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\AttachmentID;
use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use App\Models\Weapon;
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
                'weapons' => Weapon::all(),
                'attachments' => Attachment::all(),
                'attachment_weapon' => DB::table('attachment_weapon')->get(),
                'attachment_ids' => AttachmentID::all(),
            ]
        );
    }
}
