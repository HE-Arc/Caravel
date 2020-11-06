<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use App\Models\Group;

class CheckGroup
{
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
        
        if (!empty($group) && !($group instanceof Group)) $group = Group::find($group);

        if (!empty($group)) {
            if (!$group->usersApproved->contains(auth()->user())) abort(403);
        }
            
        return $next($request);
    }
}
