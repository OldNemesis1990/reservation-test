<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                if ($user) {
                    $role = $user->roles->pluck('name')->first();

                    // Redirect based on user role
                    if($role == 'guest' || $role == 'vip') {
                        return redirect(RouteServiceProvider::PROFILE);
                    }
                    elseif($role == 'admin' || $role == 'manager' || $role == 'clerk') {
                        return redirect(RouteServiceProvider::RESERVATIONS);
                    } else {
                        return redirect(RouteServiceProvider::PROFILE);
                    }
                }
            }
        }

        return $next($request);
    }
}
