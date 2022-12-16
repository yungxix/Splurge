<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SplurgeAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $ability, $guard = 'web')
    {
        $user = $request->user($guard);
        if ($user->cannot($ability)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'You are not authorized to access this resource'], 403);
            }
            return abort(403);
        }
        return $next($request);
    }
}
