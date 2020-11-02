<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tasktype;

class SeedingTest extends TestCase
{
    /**
     * A basic unit test that verify the users where inserted during seeding
     *
     * @return void
     */
    public function testUsersInserted()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'Admin Admin',    //basic argon user seeder
            'name' => 'test1'           //basic user->group seeder
        ]);
    }

    public function testGroupsInserted()
    {
        $this->assertDatabaseHas('groups', [
            'name' => 'Inf1-DLM',    //Test that at least 1 group is inserted
        ]);
    }

    public function testUsersGroupsInserted()
    {
        $this->assertDatabaseHas('group_user', [
            "user_id" => 10000,
            "group_id" => 1,
            "group_id" => 2,
            "group_id" => 3,
            'isApprouved' => 0,
            'isApprouved' => 1,
            'isApprouved' => 2
        ]);
    }

    public function testTasktypeInserted(){
        foreach(TaskType::TYPES_KEY as $label => $id) {
            $this->assertDatabaseHas('tasktypes', [
            'id' => $id,
            'name' => $label,
            ]);
        }
    }
}
