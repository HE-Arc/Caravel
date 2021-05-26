<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the Action
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the actionType that owns the Action
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actionType(): BelongsTo
    {
        return $this->belongsTo(ActionType::class,);
    }

    /**
     * Get the parent's action (task, comment, question, etc...)
     */
    public function actionable() {
        return $this->morphTo();
    }
}
