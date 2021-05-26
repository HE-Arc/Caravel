<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Group;
use Illuminate\Support\Str;

class JsonApiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testLivesearchAPIFreeGroup()
    {
        //search a free, randomized, available name
        do{
            $groupName = Str::random(120);
        }while(Group::where('name', $groupName)->count() > 0);
        //response should give "valid" as the name is not taken
        $response = $this->json('GET','groups/filtered/'.$groupName);    
        $response->assertJson(['valid' => true,]);
    }

    public function testLivesearchAPITakenGroup()
    {
        //Find a random existing group name
        $groupName = Group::inRandomOrder()->first()->name;
        //first response should give "valid"
        $response = $this->json('GET','groups/filtered/'.$groupName);    
        $response->assertJson(['valid' => false,]);
    }

    public function testLivesearchAPISuggestion()
    {
        $valid = false;
        $groupName="";
        while($valid == false){
            //Find a random existing group name
            $groupName = Group::inRandomOrder()->first()->name;

            //chunk it to where it is not taken
            do{
                $groupName = substr($groupName, 0, -1);
                $valid = Group::where('name', $groupName)->count() == 0;
            }while($valid == false || $groupName == "");
        }
        //filetered response should give at least 1 group
        $response = $this->json('GET','groups/filtered/'.$groupName);  
        //group name must be valid  
        $response->assertJson(['valid' => true,]);
        //at least 1 group must be suggested
        $response = json_decode($response->getContent(), true);
        $groups = $response['groups'];
        $this->assertTrue(count($groups) > 0);
    }

}
