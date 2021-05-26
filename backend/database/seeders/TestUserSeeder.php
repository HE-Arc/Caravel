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
            'id' => 10000,
            'name' => 'test1',
            'email' => 'test1@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('test1'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
