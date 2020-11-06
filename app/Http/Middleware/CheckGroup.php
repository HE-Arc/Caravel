<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\Group;

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
        
        if (!empty($group) && !($group instanceof Group)) $group = Group::find($group);

        if (empty($group) || ($group instanceof Group && !$group->usersApproved->contains($user))) {
            abort(403);
        }

        $request->session()->put(CheckGroup::SESSION_LAST_GROUP_ID, $group->id);
            
        return $next($request);
    }

    private function getDefaultGroup(Request $request, $user) {
        $group = $request->session()->pull(CheckGroup::SESSION_LAST_GROUP_ID, '');

        if (empty($group)) {
            $group = $user->groupsAvailable()->limit(1)->pluck('id')->first();
        }

        return $group;
    }
}
