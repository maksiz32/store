<?php

namespace App\Http\Controllers\Auth;

use App\Dictionaries\UsersRoles;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $isAdminUserRole = Auth::user() && Auth::user()->users_role_id === UsersRoles::USER_ROLES['Admin'];
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'users_role_id' => ['required', 'integer', Rule::in([
                    UsersRoles::USER_ROLES['Customer'],
                    UsersRoles::USER_ROLES['Client'],
                    UsersRoles::USER_ROLES['Admin'],
                ])],
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'store_name' => 'sometimes|nullable|string|max:255',
        ]);

        /** @var User $user */
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'users_role_id' => $validated['users_role_id'],
        ]);

        if ($isAdminUserRole && $user && $user->users_role_id === UsersRoles::USER_ROLES['Client']) {
            $store = new Store([
                 'name_store' => $validated['store_name'] ?: 'New created Store',
                 'description' => 'Store was created as empty Store. You can change Name and Description',
            ]);
             $user->store()->save($store);
        }

        if (!$isAdminUserRole) {
            event(new Registered($user));
            Auth::login($user);
        } else {
            return redirect()->route('admin.users');
        }

        return redirect(RouteServiceProvider::chooseHomepageByRole());
    }
}
