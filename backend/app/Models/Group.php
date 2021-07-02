<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class Group extends Model
{
    use HasFactory;

    //Const status code for the request status linking group and user
    public const PENDING = 0;
    public const REFUSED = 1;
    public const ACCEPTED = 2;
    public const REQUESTSTATUS = array(Group::PENDING, Group::ACCEPTED, Group::REFUSED);

    protected $fillable = [
        'name',
        'description',
        'isPrivate',
        'user_id',
    ];

    protected $hidden = [
        'subscription',
    ];

    protected $appends = [
        'metadata',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }

    public function members()
    {
        return $this->users()->select(['users.id', 'users.name', 'users.firstname', 'users.lastname', 'users.picture', 'users.isTeacher', 'users.email'])->addSelect('group_user.isApprouved as status');
    }

    public function usersAccepted()
    {
        return $this->users()->wherePivot('isApprouved', Group::ACCEPTED);
    }

    /**
     * Scoped function for users
     * 
     * @param $state Group::REQUESTATUS
     */
    public function scopeState(Builder $query, $state): Builder
    {
        return $query->whereHas('users', function (Builder $q) use ($state) {
            $q->where('isApprouved', $state);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function subjects()
    {
        return $this->hasMany('App\Models\Subject');
    }

    /**
     * Get all of the GroupStat for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function GroupStat(): HasMany
    {
        return $this->hasMany('App\Models\GroupStat');
    }

    /**
     * Get all of the GroupStat for the Group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tasks(): HasManyThrough
    {
        $userid =  auth()->user()->id;
        return $this->hasManyThrough('App\Models\Task', 'App\Models\Subject')
            ->select(['tasks.*'])
            ->orderBy('due_at', 'asc')
            ->where(function ($query) use ($userid) {
                $query->where('isPrivate', '=', 0)
                    ->orWhere('isPrivate', '=', 1)->where('user_id', '=', $userid);
            })->with('questions.comments');
    }

    /**
     * Safely delete the picture
     */
    public function removePicture()
    {
        //verify existence both in group and in file before deleting
        if (isset($this->picture) && File::exists(public_path($this->picture))) {
            File::delete(public_path($this->picture));
        }
    }

    public function getStorageFolder()
    {
        return config('caravel.groups.pictureFolder') . $this->id;
    }

    /**
     * Delete the data (ex : files from comment) of the group in storage
     */
    public function deleteStorage()
    {
        $disks = array('public', 'public_uploads');
        //if the group has a storage, delete the entire directory (files)
        foreach ($disks as $disk) {
            if (Storage::disk($disk)->exists($this->getStorageFolder())) {
                Storage::disk($disk)->deleteDirectory($this->getStorageFolder());
            }
        }
    }

    /**
     * Safely delete the picture of the group if it exists
     */
    public function deletePicture()
    {
        //verify existence both in group and in file before deleting
        if (isset($this->picture) && File::exists(public_path($this->picture))) {
            File::delete(public_path($this->picture));
        }
    }

    /**
     * Override parent delete to 
     * delete the group, including its picture 
     * and its eventual storage datas
     */
    public function delete(): bool
    {
        $this->deletePicture();
        $this->deleteStorage();
        return parent::delete();
    }

    public static function getFilteredGroupsForUser($userId, $text)
    {
        return Group::query()
            ->select('groups.*', 'group_user.isApprouved as status')
            ->leftJoin('group_user', function ($join) use ($userId) {
                $join->on('group_user.group_id', '=', 'groups.id');
                $join->on('group_user.user_id', '=', DB::raw($userId));
            })
            ->where('name', 'LIKE', "%$text%")
            ->where(function ($query) {
                $query->where('isApprouved', '!=', Group::ACCEPTED)
                    ->orWhereNull('isApprouved');
            });
    }

    /**
     * Add full path of picture in serialization process.
     *
     * @return string
     */
    public function getPictureAttribute($value)
    {
        return $value ? URL::to('/') . '/uploads' . $value : $value;
    }

    public function getMetadataAttribute()
    {
        return [
            "members" =>  $this->members()->count(),
            "subjects" => $this->subjects()->count(),
            "tasks" => $this->tasks()->count(),
        ];
    }
}
