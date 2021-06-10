<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticatesWithLdap;

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
    ];

    /**
     * Ldap objectclass attribute
     * 
     */
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pivot',
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
        return $this->belongsToMany(Group::class)
            ->withPivot('isApprouved')
            ->as('subscription')
            ->withTimestamps();
    }

    /**
     * Scoped function for users
     * 
     * @param $state Group::REQUESTATUS
     */
    public function scopeState(Builder $query, $state): Builder
    {
        return $query->whereHas('groups', function (Builder $q) use ($state) {
            $q->where('isApprouved', $state);
        });
    }

    public function groupsAvailable()
    {
        return $this->groups()->wherePivot('isApprouved', Group::ACCEPTED);
    }

    /**
     * The Notifications that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Action::class)->wherePivot('isTrash', 0);
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

    /**
     * This function delete user's picture profile
     */
    public function deletePicture()
    {
        $filepath = config("filesystems.disks.public_uploads.root") . $this->picture;
        if (File::exists($filepath)) {
            File::delete($filepath);
            $this->picture = null;
        }
    }

    /**
     * Utility fonction to set profile picture
     */
    public function setProfilePic(string $filepath)
    {
        $this->deletePicture();

        $this->picture = $filepath;
    }

    /**
     * Add full path of picture in serialization process
     *
     * @return string
     */
    public function getPictureAttribute($value)
    {
        return $value ? URL::to('/') . '/uploads' . $value : "";
    }
}
