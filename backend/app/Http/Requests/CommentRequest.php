<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|string',
            'question_id' => "sometimes|exists:App\Models\Question,id",
            'reply_to' => "sometimes|exists:App\Models\Comment,id"
        ];
    }
}
