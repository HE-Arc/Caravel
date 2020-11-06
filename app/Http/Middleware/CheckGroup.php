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

        if ($group instanceof Group) {
            if (!$group->usersApproved->contains(auth()->user())) abort(403);
        } elseif (empty($group)) {
            abort(500); // this case is not possible unless this request is an attack so we abort it
        }
            
        return $next($request);
    }
}
