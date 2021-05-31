<?php

namespace App\Http\Requests;

use App\Models\Subject;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group, Subject $subject)
    {
        // caution : subject doesnt contain an id if is in creation mode 
        return [
            'name' => "required", "max:255", Rule::unique('subjects')->ignore($subject->id)->Where('group_id', $group->id),
            'color' => 'required|size:6',
            'ects' => 'required|integer'
        ];
    }
}
