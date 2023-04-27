<?php

namespace App\Http\Middleware;

use App\Dictionaries\UsersRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && Auth::user()->users_role_id !== UsersRoles::USER_ROLES['Admin']) {
            abort(403);
        }
        return $next($request);
    }
}
