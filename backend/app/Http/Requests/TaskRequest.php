<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\Task;
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
    public function rules()
    {
        $groupId = $this->group->id;
        $isProject = $this->input('tasktype_id') == Tasktype::PROJECT;
        return [
            'title' => 'required|max:255',
            'subject_id' => "required|exists:subjects,id,group_id,{$groupId}",
            'start_at' => $isProject ? 'required|date|after_or_equal:today' : 'date',
            'due_at' => 'required|date|after_or_equal:start_at',
            'description' => 'required',
            'isPrivate' => 'boolean',
            'tasktype_id' => ['required', 'integer', Rule::in(TaskType::TYPES)]
        ];
    }
}
