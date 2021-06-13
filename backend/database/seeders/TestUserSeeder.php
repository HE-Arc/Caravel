<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'test',
            'email' => 'steve.mendesreis@he-arc.ch',
            'password' => Hash::make('test'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
