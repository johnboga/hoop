<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCompletedRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (auth()->check() && !auth()->user()->hasCompletedRegistration()) {
            if($guards[0] == 'api'){
                return response(['message' => 'complete_registration'], 400);
            }
            return redirect(route('register.complete'));
        }

        return $next($request);
    }
}
