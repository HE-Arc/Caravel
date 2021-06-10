<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $groupId = $this->group->id;
        return [
            'name' => "unique:groups,name," . $groupId . "|max:150",
            'description' => 'max:500',
            'isPrivate' => 'boolean',
            'picture' => 'image|max:4096',
            'user_id' => Rule::exists('group_user', 'user_id')->where(function ($query) use ($groupId) {
                $query->where('group_id', $groupId)
                    ->where('isApprouved', Group::ACCEPTED);
            }),
        ];
    }
}
