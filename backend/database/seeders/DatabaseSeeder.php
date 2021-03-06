<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([TestUserSeeder::class, GroupsSeeder::class]);
        $this->call([TasktypeSeeder::class, Group_UserSeeder::class]);
    }
}
