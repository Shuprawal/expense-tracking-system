<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();
//        $role = $user->getRole();

        $routeName = $request->route()->getName();

        $publicRoutes = [
            'forecasts.index',
            'forecasts.report',
            'forecasts.calculation',
            'forecast',
            'forecast.percentage',
            'categories.show',
            'welcome',
            'user.dashboard',
            'categories.index',
            'categories.create',
            'categories.store',
            'categories.display',
            'categories.transfer',
            'categories.show',
            'new-categories'

        ];

        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }



        if ($user->hasAdminRole()) {
            return $next($request);
        }


        $roles = $user->getRole();
        foreach ($roles as $role) {
            if ($role->hasPermission($routeName, $role->id)) {
                return $next($request);
            }
        }

        abort(401);

    }
}
