<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Group_UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //call seeder to mae sure userID 10'000 exists
        $this->call([
            TestUserSeeder::class,
        ]);

        //prepare insertions with 2 basic links
        $insertions = [
                [
                    "userID" => 10000,
                    "groupID" => 1,
                    "approved" => 0
                ],
                [
                    "userID" => 10000,
                    "groupID" => 2,
                    "approved" => 1
                ],
                [
                    "userID" => 10000,
                    "groupID" => 3,
                    "approved" => 2
                ]
            ];

        //insert each insertion
        foreach($insertions as $insertion){
            DB::table('group_user')->insert([
                'user_id' => $insertion["userID"],
                'group_id' => $insertion["groupID"],
                'isApprouved' => $insertion["approved"]
            ]);
        }
    }
}
