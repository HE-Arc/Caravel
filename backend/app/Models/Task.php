<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class Task extends Model
{
    use HasFactory, SoftDeletes, \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'start_at',
        'due_at',
        'isPrivate',
        'tasktype_id',
        'subject_id',
        'tasktype_id'
    ];

    protected $hidden = [
        'reactions',
    ];

    protected $appends = [
        'reactions_list',
        'has_finished'
    ];

    /**
     * Get the group's owner.
     */
    public function group()
    {
        return $this->belongsToThrough('App\Models\Group', 'App\Models\Subject');
    }

    /**
     * Get the subject that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the author that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The subscriptions that belong to the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get all of the questions for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get all of the reactions for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function getReactionsListAttribute()
    {
        $data = [];
        $react = [];
        $reactionList = [];

        foreach ($this->reactions as $reaction) {
            if (!isset($data[$reaction->type])) $data[$reaction->type] = [];
            $data[$reaction->type][] = $reaction;
            if ($reaction->user_id === Auth()->id()) $react[] = $reaction->type;
        }

        foreach ($data as $key => $reactions) {
            $reactionList[] = [
                'type' => $key,
                'count' => count($reactions),
                'hasReact' => in_array($key, $react),
            ];
        }

        return $reactionList;
    }

    public function getHasFinishedAttribute()
    {
        $user_id = Auth()->id();
        if (empty($user_id)) return false;
        return $this->subscriptions()->wherePivot('user_id', $user_id)->wherePivot('hasFinished', "1")->count() > 0;
    }

    public function updateFinish($user_id, $hasFinished)
    {
        $data = ['hasFinished' => $hasFinished];
        $update = $this->subscriptions()->updateExistingPivot($user_id, $data);

        if (empty($update)) {
            $this->subscriptions()->attach($user_id, $data);
        }
    }
}
