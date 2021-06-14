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
            'name' => 'admin',
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'steve.mendesreis@he-arc.ch',
            'password' => Hash::make('test'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'test',
            'firstname' => 'test',
            'lastname' => 'test',
            'email' => 'qwdqwd@testqwd.ch',
            'password' => Hash::make('test'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
