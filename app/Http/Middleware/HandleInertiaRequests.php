<?php

namespace App\Http\Middleware;

use App\Dictionaries\UsersRoles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        if (Auth::user() && Auth::user()->users_role_id !== UsersRoles::USER_ROLES['Admin']) {
            $user = User::where('id', Auth::id())
                ->with(['store', 'categories'])
                ->first();
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
