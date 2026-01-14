<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\AttachmentID;
use App\Models\Filter;
use App\Models\Game;
use App\Models\Map;
use App\Models\Weapon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            throw new \Exception('Failed to fetch data from production site: '.$response->body());
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

        Weapon::truncate();
        foreach ($data['weapons'] as $weapon) {
            Weapon::create([
                'id' => $weapon['id'],
                'type' => $weapon['type'],
                'name' => $weapon['name'],
                'code_prefix' => $weapon['code_prefix'],
            ]);
        }

        Attachment::truncate();
        foreach ($data['attachments'] as $attachment) {
            Attachment::create([
                'id' => $attachment['id'],
                'type' => $attachment['type'],
                'name' => $attachment['name'],
                'label' => $attachment['label'],
                'code_base34' => $attachment['code_base34'],
                'code_base10' => $attachment['code_base10'],
                'weapon_unlock' => $attachment['weapon_unlock'],
            ]);
        }

        DB::table('attachment_weapon')->truncate();
        foreach ($data['attachment_weapon'] as $aw) {
            DB::table('attachment_weapon')->insert([
                'attachment_id' => $aw['attachment_id'],
                'weapon_id' => $aw['weapon_id'],
                'order' => $aw['order'],
            ]);
        }

        AttachmentID::truncate();
        foreach ($data['attachment_ids'] as $id) {
            AttachmentID::create([
                'id' => $id['id'],
                'base_10' => $id['base_10'],
                'base_34' => $id['base_34'],
                'k' => $id['k'],
                'n' => $id['n'],
            ]);
        }
    }
}
