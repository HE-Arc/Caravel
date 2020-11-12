<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    //Const status code for the request status linking group and user
    public const PENDING = 0;
    public const REFUSED = 1;
    public const ACCEPTED = 2;
    public const REQUESTSTATUS = array(Group::PENDING, Group::ACCEPTED, Group::REFUSED);

    public function pictureOrDefault(){
        return $this->picture ?? asset(config('caravel.groups.pictureFolder').config('caravel.groups.pictureBase'));
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }
    
    public function usersWithSubscription(){
        return $this->belongsToMany('App\Models\User')->withPivot('isApprouved')->withTimestamps();
    }

    public function usersApproved(){
        return $this->belongsToMany('App\Models\User')->withTimestamps()->wherePivot('isApprouved', Group::ACCEPTED);
    }

    public function usersRefused(){
        return $this->belongsToMany('App\Models\User')->withTimestamps()->wherePivot('isApprouved', Group::REFUSED);
    }

    public function usersRequesting(){
        return $this->belongsToMany('App\Models\User')->withTimestamps()->wherePivot('isApprouved', Group::PENDING);
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
