<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Get the group's owner.
     */
    public function group()
    {
        return $this->hasOneThrough('App\Models\Group', 'App\Models\Subject');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function taskType()
    {
        return $this->belongsTo('App\Models\Tasktype');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function related()
    {
        return $this->belongsToMany('App\Models\Task', 'related_tasks', 'related_id', 'task_id');
    }

    public function attachements()
    {
        return $this->hasMany('App\Models\Attachement');
    }

}
