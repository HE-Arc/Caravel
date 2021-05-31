<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tasktype;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group)
    {
        return [
            'title' => 'required|max:255',
            'subject_id' => "required|exists:subjects,id,group_id,{$group->id}",
            'start_at' => 'required|date|after_or_equal:today',
            'due_at' => 'required|date|after_or_equal:start_at',
            'description' => 'required',
            'isPrivate' => 'boolean',
            'tasktype_id' => ['required', 'integer', Rule::in(TaskType::TYPES)]
        ];
    }
}
