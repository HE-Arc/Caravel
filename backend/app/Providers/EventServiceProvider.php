<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\Question;
use App\Models\Comment;
use App\Observers\QuestionObserver;
use App\Observers\TaskObserver;
use App\Observers\CommentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Task::observe(TaskObserver::class);
        //Question::observe(QuestionObserver::class);
        //Comment::observe(CommentObserver::class);
    }
}
