<?php

namespace App\Http\Middleware;

use App\Support\OneTimeAccessService;
use Closure;
use Illuminate\Http\Request;

class SplurgeAccessMiddleware
{
    private $service;
    public function __construct(OneTimeAccessService $service)
    {
        $this->service = $service;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->service->check()) {
            return abort(402);
        }
        return $next($request);
    }
}
