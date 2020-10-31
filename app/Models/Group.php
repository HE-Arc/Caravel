<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function author()
    {
        return $this->hasOne('App\Models\User');
    }

    public function subjects()
    {
        return $this->hasMany('App\Models\Subject');
    }

    public function tasks() {

        return $this->hasManyThrough('App\Models\Task', 'App\Models\Subject');

    }
}
