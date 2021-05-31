<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RemoveGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->merge(['group_id' => $this->route('group')]);
        return [
            'group_id' => "required", Rule::exists('group_user','group_id')->where(function($query) {
                $query->where('isApproved', Group::ACCEPTED)->where('user_id', Auth::id());
            }),
        ];
    }
}
