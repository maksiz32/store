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
        return Inertia::render('Admin/CRMStores/Users', [
            'users' => User::with(['users_roles', 'store'])->get(),
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
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
