<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasktype extends Model
{
    use HasFactory;

    public const ASSIGNMENT = 1;
    public const EXAM = 2;
    public const PROJECT = 3;
    public const TYPES = array(TaskType::ASSIGNMENT, TaskType::EXAM, TaskType::PROJECT);
    public const TYPES_KEY = array("Assignment" => TaskType::ASSIGNMENT,
                                   "Exam" => TaskType::EXAM,
                                   "Project" => TaskType::PROJECT);

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
