<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group, Comment $comment)
    {
        return [
            'description' => 'required',
            'question_id' => "required|exists:App\Models\Question,id,group_id,{$group->id}"
        ];
    }
}
