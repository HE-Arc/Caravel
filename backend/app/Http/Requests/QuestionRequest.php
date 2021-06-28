<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required_with:task_id|string',
            'title' => 'required_with:task_id|string',
            'task_id' => 'sometimes|exists:tasks,id',
        ];
    }
}
