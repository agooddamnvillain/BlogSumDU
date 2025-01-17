<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $role = Auth::user()->role->id;

            switch ($role) {
                case 1:
                    return redirect()->route('admin.dashboard');
                    break;
                case 2:
                    return redirect()->route('author.dashboard');
                    break;
                default:
                    return $next($request);
            }
        }

        return $next($request);
    }
}
