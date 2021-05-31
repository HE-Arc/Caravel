<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'ects',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
