<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\View;


/**
* Check if there is a group set on the request and valid auth group
**/
class CheckGroup
{
    const SESSION_LAST_GROUP_ID = 'lastGroupId';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $group = $request->route('group');

        $user = auth()->user();

        if (empty($group)) {
            $group = $this->getDefaultGroup($request, $user);
        }
        
        if (!empty($group) && !($group instanceof Group)) {
            $group = Group::find($group);
            View::share('group', $group);
        }

        if (empty($group) || ($group instanceof Group && !$group->usersApproved->contains($user))) {
            abort(403);
        } else {
            //share info for sidebar
            View::share([
                'groupMembersCount' => $group->usersApproved->count(),
                'groupRequestsCount' => $group->usersRequesting->count(),
            ]);
        }

        $request->session()->put(CheckGroup::SESSION_LAST_GROUP_ID, $group->id);
            
        return $next($request);
    }

    private function getDefaultGroup(Request $request, $user) {
        $group = $request->session()->pull(CheckGroup::SESSION_LAST_GROUP_ID, '');

        if (!empty($group) && (empty($user->groupsAvailable) || !$user->groupsAvailable->contains($group))) {
            $request->session()->forget(CheckGroup::SESSION_LAST_GROUP_ID);
            $group = null;
        }

        if (empty($group)) {
            if (!empty($user->groupsAvailable) && !empty($user->groupsAvailable->first()))
                $group = $user->groupsAvailable->first()->id;
        }
        
        return $group;
    }
}
