<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Game;
use App\Models\GameVersion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate Admins
        foreach (range(1,2) as $index) {
            DB::table('admins')->insert([
                'username'=>"admin$index",
                'password'=>"hellouniverse $index!",
                "created_at"=>now()
            ]);
        }

        // Generate Developers
        foreach (range(1,2) as $index) {
            DB::table('users')->insert([
                'username'=>"dev $index",
                'password'=>"hellobyte $index!",
                "created_at"=>now()
            ]);
        }

        // Generate Games
        foreach (range(1,2) as $index) {
            DB::table('games')->insert([
                'title'=>"Demo Game  $index",
                'slug'=>Str::slug("Demo Game  $index"),
                'description'=>"This is demo game  $index",
                "user_id"=>DB::table('users')->inRandomOrder()->first()->id,
                "created_at"=>now()
            ]);
        }

        // Generate Game Versions
        foreach (range(1,2) as $index) {
            $game = Game::where(['title'=>"Demo Game  $index"])->first();
            foreach (range(1, random_int(2,5)) as $index_) {
                DB::table('game_versions')->insert([
                    'game_id' => $game->id,
                    'version' => $index_,
                    "created_at"=>now()
                ]);
            }
        }

        // Generate Players
        foreach (range(1,2) as $index) {
            DB::table('users')->insert([
                'username'=>"player $index",
                'password'=>"helloworld $index!",
                "created_at"=>now()
            ]);
        }

        // Generate Scores
        $game_versions = GameVersion::all();
        foreach ($game_versions as $game_version) {
            foreach (range(1, random_int(3, 7)) as $index) {
                DB::table('scores')->insert([
                    'game_version_id'=>$game_version->id,
                    'user_id'=>DB::table('users')->inRandomOrder()->first()->id,
                    'score'=>random_int(50, 1000),
                    "created_at"=>now()
                ]);
            }
        }


        DB::table('reasons')->insert([
            'title'=>"You have been blocked by an administrator"
        ]);
        DB::table('reasons')->insert([
            'title'=>"You have been blocked for spamming"
        ]);
        DB::table('reasons')->insert([
            'title'=>"You have been blocked for cheating"
        ]);

        DB::table('banned_users')->insert([
            'user_id'=>DB::table('users')->inRandomOrder()->first()->id,
            'reason_id'=>DB::table('reasons')->inRandomOrder()->first()->id
        ]);
    }
}
