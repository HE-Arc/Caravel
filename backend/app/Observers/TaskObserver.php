<?php

namespace App\Observers;

use App\Models\Group;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use App\Notifications\Action;
use App\Notifications\ActionType;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    /** @var User $user */
    protected $user;


    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
    }

    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $this->sendNotification($task, 'create', ActionType::CREATE);
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        $this->sendNotification($task, 'update', ActionType::UPDATE);
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $this->sendNotification($task, 'delete', ActionType::DELETE);
    }

    private function sendNotification($task, $action, $type)
    {
        /** @var Group */
        $subject = Subject::with('group')->find($task->subject_id);

        $group = $subject->group;

        $users = $group->usersAccepted;

        foreach ($users as $user) {
            if ($user->id != $user->id) {
                $user->notify(new Action(
                    __("api.notifications.task.${action}.title", ['name' => $task->title]),
                    __("api.notifications.task.${action}.message", ['name' => $task->title, 'group' => $group->name]),
                    $task,
                    $type,
                    $group,
                ));
            }
        }
    }
}
