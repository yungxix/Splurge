<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class OneTimeCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = 'api')
    {
        $credentails = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if (Auth::guard($guard)->once($credentails)) {
            return $next($request);
        }
        return response()->json([
            'message' => trans('auth.failed')
        ], 422);
    }
}
