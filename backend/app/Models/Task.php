<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            $reactionList[$key] = [
                'count' => count($reactions),
                'hasReact' => in_array($key, $react),
            ];
        }

        return $reactionList;
    }
}
