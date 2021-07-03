<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\ActionType;
use App\Notifications\Action;

class CommentObserver extends AbstractActionObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $this->sendNotification($comment, 'create', ActionType::CREATE);
    }


    private function sendNotification(Comment $comment, $action, $type)
    {
        $comment->load('question.task.group', 'task.author', 'question.commentsFlat');

        $group = $comment->question->task->group;

        $users = $comment->question->contributors;

        foreach ($users as $user) {
            if ($this->user->id != $user->id) {
                $user->notify(new Action(
                    __("api.notifications.comment.${action}.title"),
                    __("api.notifications.comment.${action}.message", ['group' => $group->name]),
                    $comment->question->task,
                    $type,
                    $group,
                ));
            }
        }
    }
}
