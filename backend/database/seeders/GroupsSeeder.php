<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;

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
            //select random user as pseudo-creator
            $user = User::inRandomOrder()->first();
            DB::table('groups')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => 1,//set him as leader
            ]);
            //attach him
            $group = Group::where('name', $name)->first();
            $group->users()->attach($user->id, ['isApprouved' => Group::ACCEPTED]);
        }
    }
}
