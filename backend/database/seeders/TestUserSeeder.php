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
            'id' => 11,
            'name' => 'awo-etu',
            'firstname' => 'awo-etu',
            'lastname' => 'awo-etu',
            'email' => 'awo-etu@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 12,
            'name' => 'awo-prof',
            'firstname' => 'awo-prof',
            'lastname' => 'awo-prof',
            'email' => 'awo-prof@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 13,
            'name' => 'dgr-etu',
            'firstname' => 'dgr-etu',
            'lastname' => 'dgr-etu',
            'email' => 'dgr-etu@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 14,
            'name' => 'dgr-prof',
            'firstname' => 'dgr-prof',
            'lastname' => 'dgr-prof',
            'email' => 'dgr-prof@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 15,
            'name' => 'jpe-etu',
            'firstname' => 'jpe-etu',
            'lastname' => 'jpe-etu',
            'email' => 'jpe-etu@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 16,
            'name' => 'jpe-prof',
            'firstname' => 'jpe-prof',
            'lastname' => 'jpe-prof',
            'email' => 'jpe-prof@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 17,
            'name' => 'jne-etu',
            'firstname' => 'jne-etu',
            'lastname' => 'jne-etu',
            'email' => 'jne-etu@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 18,
            'name' => 'jne-prof',
            'firstname' => 'jne-prof',
            'lastname' => 'jne-prof',
            'email' => 'jne-prof@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 19,
            'name' => 'sbe-etu',
            'firstname' => 'sbe-etu',
            'lastname' => 'sbe-etu',
            'email' => 'sbe-etu@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'id' => 20,
            'name' => 'sbe-prof',
            'firstname' => 'sbe-prof',
            'lastname' => 'sbe-prof',
            'email' => 'sbe-prof@test.ch',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
