<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // caution : subject doesnt contain an id if is in creation mode 
        $groupId = $this->group->id;
        $subjectId = $this->subject ? $this->subject->id : null;
        return [
            'name' => ["required", "max:255", Rule::unique('subjects')->ignore($subjectId)->where(function ($query) use ($groupId) {
                return $query->where('group_id', $groupId);
            })],
            'color' => 'required|size:7',
            'ects' => 'required|integer|min:1',
            'description' => 'nullable|max:4096'
        ];
    }
}
