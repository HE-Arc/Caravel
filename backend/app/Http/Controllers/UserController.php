<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveGroupRequest;
use App\Models\Group;

class UserController extends Controller
{
    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\RemoveGroupRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeGroup(RemoveGroupRequest $request)
    {
        $data = $request->validated();

        $this->user->groups()->updateExistingPivot($data['group_id'], ["isApprouved" => Group::LEFT], true);

        return response()->json(['message' => __('api.users.remove_group')]);
    }
}
