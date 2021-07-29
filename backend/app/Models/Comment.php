<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'question_id',
        'reply_to',
    ];

    protected $with = ['replyTo'];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo('App\Models\Question');
    }

    /**
     * Get the replyTo associated with the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replyTo(): HasMany
    {
        return $this->hasMany(Comment::class, 'reply_to');
    }

    public function getDescriptionAttribute($value)
    {
        return $this->removed ? __('api.comments.removed') : $value;
    }
}
