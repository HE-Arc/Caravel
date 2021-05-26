<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tasktype;
use Illuminate\Support\Facades\DB;

class TasktypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(TaskType::TYPES_KEY as $label => $id) {
            DB::table('tasktypes')->insert([
                'id' => $id,
                'name' => $label,
            ]);
        }
    }
}
