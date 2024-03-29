<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Question;

/**
 * This class manage comments
 */
class CommentController extends Controller
{
    /**
     * This function is used to store a comment
     * 
     * @param CommentRequest $request
     * @param Group $group
     * @return Question question linked with the comment
     */
    public function store(CommentRequest $request, Group $group)
    {
        $max = Comment::where('question_id', $request->question_id)->max('comment_question_id');
        $comment = new Comment();
        $comment->comment_question_id = $max + 1;
        $comment->user_id = Auth()->id();
        return $this->persistData($request, $group, $comment);
    }

    /**
     * this function allow updating a comment
     * 
     * @param CommentRequest $request
     * @param Group $group
     * @param Comment $comment
     * @return Question return question if ok, 403 if not
     */
    public function update(CommentRequest $request, Group $group, Comment $comment)
    {
        if ($this->user->id == $comment->user->id) {
            return $this->persistData($request, $group, $comment);
        }
        return response()->json(__('api.global.access_denied'), 403);
    }

    /**
     * This is an upsert function allow to create or update a comment
     * @param CommentRequest $request
     * @param Group $group
     * @param Comment $comment
     * @return Question question linked with the comment
     */
    protected function persistData(CommentRequest $request, Group $group, Comment $comment)
    {
        if ($comment->removed) return response(
            "update not possible on this data",
            403
        ); // if attempt has been made to put data on a removed data, it may be an attack
        $comment->fill($request->validated());
        $comment->save();
        $comment->load('question.comments');
        return $comment->question;
    }

    /**
     * Remove the specified comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, Comment $comment)
    {
        if ($this->user->id == $comment->user_id) {
            $questionId = $comment->question_id;

            if ($comment->replyTo()->count() > 0) {
                $comment->removed = true;
                $comment->save();
            } else {
                $comment->delete();
            }

            $question = Question::with('comments')->find($questionId);

            return $question;
        }

        return response()->json(__('api.comments.delete_faild'), 403);
    }
}
