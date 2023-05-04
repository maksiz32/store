<?php

namespace App\Providers;

use App\Dictionaries\UsersRoles;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    private const CLIENT_HOME = '/store';
    private const ADMIN_HOME = '/admin-panel';
    private const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('ajax')
                ->group(base_path('routes/ajax.php'));
        });
    }

    public static function chooseHomepageByRole()
    {
        $userRole = Auth::user()->users_role_id;
        if ($userRole === UsersRoles::USER_ROLES['Admin']) {
            return self::ADMIN_HOME;
        }

        if ($userRole === UsersRoles::USER_ROLES['Client']) {
            return self::CLIENT_HOME;
        }

        return self::HOME;
    }
}
