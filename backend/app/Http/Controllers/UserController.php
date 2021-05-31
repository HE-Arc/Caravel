<?php
namespace App\Http\Controllers;

use App\Http\Requests\RemoveGroupRequest;

class UserController extends Controller
{
    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\RemoveGroupRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeGroup(RemoveGroupRequest $request) {
        $data = $request->validated();

        $this->user->groups()->detach($data['group_id']);

        return response()->json(['message' => __('api.users.remove_group')]);
    }
}
