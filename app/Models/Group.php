<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    //default image
    protected $attributes = [
        'picutre' => config() . config(),
    ];

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
}
