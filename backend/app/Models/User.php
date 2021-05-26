<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group')
            ->withPivot('isApprouved')
            ->as('subscription')
            ->withTimestamps();
    }

    public function groupsAvailable()
    {
        return $this->groups()->wherePivot('isApprouved', Group::ACCEPTED);
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * return the picture of the user if it exist or the base picture for all users
     */
    public function getPicture()
    {
        return $this->picture ?? config('caravel.users.pictureFolder') . config('caravel.users.pictureBase');
    }

    /**
     * Get all of the Subscription for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * The settings that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function settings(): BelongsToMany
    {
        return $this->belongsToMany(ActionType::class, 'settings_user', 'user_id', 'action_type_id');
    }
}
