<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\Group;

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
            return $next($request);
        }

        if (!$group instanceof Group) {
            $group = Group::find($group);
        }

        $group->loadMissing('usersAccepted');

        if (empty($group) || !$group->usersAccepted->contains($user)) {
            abort(403, "Access denied");
        }

        return $next($request);
    }
}
