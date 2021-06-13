<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Group;


class Group_UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //prepare insertions with 2 basic links
        $insertions = [
            [
                "userID" => 2,
                "groupID" => 1,
                "approved" => Group::PENDING,
            ],
            [
                "userID" => 2,
                "groupID" => 2,
                "approved" => Group::REFUSED,
            ],
            [
                "userID" => 2,
                "groupID" => 3,
                "approved" => Group::ACCEPTED,
            ]
        ];

        //insert each insertion
        foreach ($insertions as $insertion) {
            DB::table('group_user')->insert([
                'user_id' => $insertion["userID"],
                'group_id' => $insertion["groupID"],
                'isApprouved' => $insertion["approved"]
            ]);
        }
    }
}
