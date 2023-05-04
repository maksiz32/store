<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('CRM/Admins/Users', [
            'users' => User::with(['users_roles', 'store'])
                ->orderBy('users_role_id', 'desc')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAdmin()
    {
        return Inertia::render('Auth/Register', [
            'isAdminMode' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->store()) {
            $user->store()->delete();
        }
        $user->delete();

        return redirect()->route('admin.users');
    }
}
