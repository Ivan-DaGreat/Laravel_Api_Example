<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class AccessUser
{
    /**
     * Check User Access
     * - Editors can do anything
     * - Users can only view/edit their own profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (Auth::user()->access !== 'editor' || Auth::user()->id === $request->route('id')) {
		    return response()->json(['message' => 'Insufficient Access Level'], 403);
	    }

    	return $next($request);
    }
}
