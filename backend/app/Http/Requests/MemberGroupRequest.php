<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Group $group)
    {
        $this->merge(['user_id' => $this->route('user')]);
        // caution : subject doesnt contain an id if is in creation mode 
        return [
            'user_id' => 'required', Rule::exists('group_user','user_id')->where(function($query) use ($group) {
                $query->where('group_id', $group->id);
            })
        ];
    }
}
