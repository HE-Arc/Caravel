<?php

namespace App\Observers;

use App\Models\Question;
use App\Notifications\Action;
use App\Notifications\ActionType;

class QuestionObserver extends AbstractActionObserver
{
    /**
     * Handle the Question "created" event.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function created(Question $question)
    {
        $this->sendNotification($question, 'create', ActionType::CREATE);
    }

    /**
     * Handle the Question "updated" event.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function updated(Question $question)
    {
        $this->sendNotification($question, 'update', ActionType::UPDATE);
    }


    private function sendNotification(Question $question, $action, $type)
    {
        $question->load('task.group');
        $group = $question->task->group;

        $users = $group->usersAccepted;

        foreach ($users as $user) {
            if ($this->user->id != $user->id) {
                $user->notify(new Action(
                    __("api.notifications.question.${action}.title"),
                    __("api.notifications.question.${action}.message", ['name' => $question->title, 'group' => $group->name]),
                    $question->task,
                    $type,
                    $group,
                ));
            }
        }
    }
}
