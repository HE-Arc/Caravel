<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'due_at',
    ];

    /**
     * Get the group's owner.
     */
    public function group()
    {
        return $this->hasOneThrough('App\Models\Group', 'App\Models\Subject',
                                    'group_id', // Foreign key on cars table...
                                    'subject_id', // Foreign key on owners table...
                                    'id', // Local key on mechanics table...
                                    'id' // Local key on cars table...
                                );
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function taskType()
    {
        return $this->belongsTo('App\Models\Tasktype');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function related()
    {
        return $this->belongsToMany('App\Models\Task', 'related_tasks', 'related_id', 'task_id')->withTimestamps();
    }

    public function attachements()
    {
        return $this->hasMany('App\Models\Attachement');
    }

    public function contributors() {
        return $this->belongsToMany('App\Models\User')->withTimestamps()->withPivot('created_at')->OrderbyDesc('task_user.created_at');
    }

}
