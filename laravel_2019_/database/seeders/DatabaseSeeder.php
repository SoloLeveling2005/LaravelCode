<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $orginizer = Organizer::where(['email' => 'demo1@worldskills.org'])->first();
        $orginizer->password_hash = Hash::make('demopass1');
        $orginizer->save();

        $orginizer = Organizer::where(['email' => 'demo2@worldskills.org'])->first();
        $orginizer->password_hash = Hash::make('demopass2');
        $orginizer->save();
    }
}
