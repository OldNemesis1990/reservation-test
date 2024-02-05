<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotStaff
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
                    switch ($role) {
                        case 'guest':
                        case 'vip':
                            return redirect(RouteServiceProvider::PROFILE);
                        case 'admin':
                        case 'manager':
                        case 'clerk':
                            return $next($request);
                        default:
                            // Default redirection if the user has no role or unrecognized role
                            return redirect(RouteServiceProvider::PROFILE);
                    }

                    // Redirect to /dashboard/profile if the user does not have the specified roles
                }
            }
        }

        return $next($request);
    }
}
