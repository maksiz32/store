<?php

namespace App\Http\Middleware;

use App\Dictionaries\UsersRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            (Auth::user()
            && $request['client_id']
            && (int) Auth::user()->users_role_id === UsersRoles::USER_ROLES['Client']
            && (int) Auth::id() === (int) $request['client_id'])
            || (Auth::user() && Auth::user()->users_role_id === UsersRoles::USER_ROLES['Admin'])
        ) {
            return $next($request);
        }

            return abort(403);
    }
}
