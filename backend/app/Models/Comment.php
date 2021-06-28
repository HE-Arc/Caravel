<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'question_id'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function replyTo(): HasOne
    {
        return $this->hasOne(Comment::class, 'reply_to');
    }
}
