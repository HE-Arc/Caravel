<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'task_id',
        'title',
        'solved',
    ];

    protected $with = ['solvedBy'];

    protected $appends = [
        'count_comments',
    ];

    /**
     * Get all of the comments for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('reply_to');
    }

    /**
     * Get all of the comments for the Question
     *
     * @return int
     */
    public function getCountCommentsAttribute(): int
    {
        return $this->hasMany(Comment::class)->count();
    }

    /**
     * Get the solver for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solvedBy(): BelongsTo
    {
        return $this->belongsTo(Comment::class, "solved");
    }

    /**
     * Get the task that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the author that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
