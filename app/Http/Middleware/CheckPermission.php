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
        $role = $user->getRole();

        if ($role->isAdmin()) {
            return $next($request);
        }
            $hasPermission = $role->hasPermission($request->route()->getName(), $role->id);

            if ($hasPermission) {
                return $next($request);
            } else {
                abort(401);
            }

    }
}
