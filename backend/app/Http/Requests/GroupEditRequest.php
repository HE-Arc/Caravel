<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class GroupEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group)
    {
        return [
            'name' => "unique:groups,name,{$group->id}|max:150",
            'description' => 'max:500',
            'isPrivate' => 'boolean',
            'picture' => 'image|max:4096',
        ];
    }
}
