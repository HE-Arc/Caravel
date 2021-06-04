<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
    ];

    public function pictureOrDefault()
    {
        return $this->picture ?? asset(config('caravel.groups.pictureFolder') . config('caravel.groups.pictureBase'));
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }

    public function usersWithSubscription()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('isApprouved')->withTimestamps();
    }

    /**
     * Scoped function for users
     * 
     * @param $state Group::REQUESTATUS
     */
    public function scopeState(Builder $query, $state): Builder {
        return $query->whereHas('users', function (Builder $q) use($state) {
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
        return $this->hasManyThrough('App\Models\Task', 'App\Models\Subject');
    }

    /**
     * Safely delete the picture
     */
    public function removePicture(){
        //verify existence both in group and in file before deleting
        if(isset($this->picture) && File::exists(public_path($this->picture))){ 
            File::delete(public_path($this->picture));
        }
    }

    public function getStorageFolder() {
        return config('smartmd.files.root') . "/groups/" . $this->id;
    }

    /**
     * Delete the data (ex : files from comment) of the group in storage
     */
    public function deleteStorage(){
        //if the group has a storage, delete the entire directory (files)
        if(Storage::exists($this->getStorageFolder())){
            Storage::deleteDirectory($this->getStorageFolder());
        }
    }

    /**
     * Safely delete the picture of the group if it exists
     */
    public function deletePicture(){
        //verify existence both in group and in file before deleting
        if(isset($this->picture) && File::exists(public_path($this->picture))){ 
            File::delete(public_path($this->picture));
        }
    }

    /**
     * Override parent delete to 
     * delete the group, including its picture 
     * and its eventual storage datas
     */
    public function delete(): bool {
        $this->deletePicture();
        $this->deleteStorage();
        return parent::delete();
    }

    public static function getFilteredGroupsForUser($userId, $text) {
        return Group::query()
        ->select('groups.*', 'group_user.isApprouved as status')
        ->leftJoin('group_user', function ($join) use($userId) {
            $join->on('group_user.group_id','=','groups.id');
            $join->on('group_user.user_id','=',DB::raw($userId));
        })
        ->where('name', 'LIKE', "%$text%")
        ->where(function($query) {
            $query->where('isApprouved', '!=', Group::ACCEPTED)
                  ->orWhereNull('isApprouved');
        });
    }
}
