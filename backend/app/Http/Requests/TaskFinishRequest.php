<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tasktype;
use Illuminate\Validation\Rule;

class TaskFinishRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'task_id' => "required|exists:tasks,id",
            'hasFinished' => "required|boolean",
        ];
    }
}
