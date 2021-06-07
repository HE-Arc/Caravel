<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group)
    {
        return [
            'name' => "required|unique:groups,name,{$group->id}|max:150",
            'description' => 'max:500',
            'isPrivate' => 'boolean',
            'picture' => 'image|max:4096',
            'user_id' => Rule::exists('group_user','user_id')->where(function($query) use ($group) {
                $query->where('group_id', $group->id)
                      ->where('isAccepted', Group::ACCEPTED);
            })
        ];   
    }
}
