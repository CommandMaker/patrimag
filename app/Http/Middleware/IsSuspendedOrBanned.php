<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsSuspendedOrBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (auth()->user() && auth()->user()->trashed()) {
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('security.login-view')->with('error', 'Ce compte a été banni, vous ne pouvez plus vous y connecter');
        }

        if (auth()->user() && auth()->user()->is_suspended) {
            session()->invalidate();
            session()->regenerateToken();

            return redirect()->route('security.login')->with('error', 'Ce compte a été suspendu, vous ne pouvez plus vous y connecter');
        }

        return $next($request);
    }
}
