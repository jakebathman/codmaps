<?php

namespace Database\Seeders;

use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call the /api/data endpoint on the production site to get the database contents to seed
        $response = Http::withHeaders(['X-API-KEY' => config('services.api_key')])
            ->throw()
            ->get('https://randomcod.com/api/data');

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch data from production site: ' . $response->body());
        }

        $data = $response->json();

        Game::truncate();
        foreach ($data['games'] as $game) {
            Game::create([
                'id' => $game['id'],
                'name' => $game['name'],
                'key' => $game['key'],
                'is_active' => $game['is_active'],
            ]);
        }

        Filter::truncate();
        foreach ($data['filters'] as $filter) {
            Filter::create([
                'id' => $filter['id'],
                'game_id' => $filter['game_id'],
                'name' => $filter['name'],
                'is_active' => $filter['is_active'],
            ]);
        }

        Map::truncate();
        foreach ($data['maps'] as $map) {
            Map::create([
                'id' => $map['id'],
                'name' => $map['name'],
                'image' => $map['image'],
                'game' => $map['game'],
                'filters' => $map['filters'],
                'is_active' => $map['is_active'] ?? false,
            ]);
        }
    }
}
