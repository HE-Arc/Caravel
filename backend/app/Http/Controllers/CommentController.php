<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Group;


class CommentController extends Controller
{
    public function store(CommentRequest $request, Group $group)
    {
        $comment = new Comment();
        $comment->user_id = Auth()->id();
        return $this->persistData($request, $group, $comment);
    }

    public function update(CommentRequest $request, Group $group, Comment $comment)
    {
        return $this->persistData($request, $group, $comment);
    }

    protected function persistData(CommentRequest $request, Group $group, Comment $comment)
    {
        $comment->fill($request->validated());
        $comment->save();
        $comment->load('replyTo');
        return $comment;
    }
}
