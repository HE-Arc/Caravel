<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            "Inf1-DLM",
            "Inf2-DLM",
            "inf3-DLM",
            "Inf1-IIE",
            "Inf2-IIE",
            "inf3-IIE"
        ];

        foreach ($names as $name){
            DB::table('groups')->insert([
                'id' => 0, //auto-incremented
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);    
        }
    }
}
