<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{

    // Display all users
    public function index()
    {
        $users = User::with('roles')->get(); // Load roles with users
        return view('layouts.admin.users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        $roles = Role::all(); // Get all roles
        return view('layouts.admin.users.create', compact('roles'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->assignRole($request->role); // Assign role to the user

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Show edit form
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('layouts.admin.users.edit', compact('user', 'roles'));
    }

    // Update user details
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->syncRoles([$request->role]); // Sync role

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
