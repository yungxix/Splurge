<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowTooling
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');
        if (!$header) {
            return abort(402);
        }
        $value = last(explode(' ', $header));
        if ($value !== config('app.tll')) {
            return abort(402);
        }
        return $next($request);
    }
}
