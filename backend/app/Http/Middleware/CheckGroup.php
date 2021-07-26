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
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $group = $request->route('group'); // retrieve the group in the request
        $user = auth()->user(); // get current user

        if (empty($group)) { // if there is no group, nothing to check
            return $next($request);
        }

        if (!$group instanceof Group) { // if the group is not an instance of Group try to get one  
            $group = Group::find($group);
        }

        $group->loadMissing('usersAccepted'); // load users accepted if not already loaded

        //Check group access, if not authorized send error to the user
        if (empty($group) || !$group->usersAccepted->contains($user)) { 
            abort(403, __("api.global.403"));
        }

        return $next($request);
    }
}
