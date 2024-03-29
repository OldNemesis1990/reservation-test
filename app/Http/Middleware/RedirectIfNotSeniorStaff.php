<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotSeniorStaff
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
                            abort(401);
                        case 'admin':
                        case 'manager':
                            return $next($request);
                        case 'clerk':
                            abort(401);
                        default:
                            // Default redirection if the user has no role or unrecognized role
                            abort(401);
                    }

                    // Redirect to /dashboard/profile if the user does not have the specified roles
                }
            }
        }

        return $next($request);
    }
}
