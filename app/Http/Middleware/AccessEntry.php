<?php

namespace App\Http\Middleware;

use App\Entries;
use Closure;
use Auth;
use App\Entry;
use Illuminate\Http\Request;

/**
 * Middleware AccessEntry
 * Validates if user is allowed to access/modify entry
 * @package App\Http\Middleware
 */
class AccessEntry
{
    /**
     * Check User Access
     * - Editors can do anything
     * - Users can only view/edit the entries they own
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (Auth::user()->access !== 'editor' || Entries::where('id', $request->route('id'))->where('user_id', Auth::user()->id)->exists()) {
		    return response()->json(['message' => 'Insufficient Access Level'], 403);
	    }

    	return $next($request);
    }
}
