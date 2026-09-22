<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Make sure only administrators can access this module.
     */
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'Admin',
            403
        );
    }

    /**
     * Display all users.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $users = User::latest()->get();

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'Active')->count();
        $inactiveUsers = User::where('status', 'Inactive')->count();
        $adminUsers = User::where('role', 'Admin')->count();
        $technicianUsers = User::where('role', 'Technician')->count();
        $supervisorUsers = User::where('role', 'Supervisor')->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'adminUsers',
            'technicianUsers',
            'supervisorUsers'
        ));
    }

    /**
     * Show create-user form.
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('users.create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'role' => [
                'required',
                Rule::in(['Admin', 'Technician', 'Supervisor']),
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display a user.
     */
    public function show(User $user)
    {
        $this->authorizeAdmin();

        return view('users.show', compact('user'));
    }

    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        return view('users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'role' => [
                'required',
                Rule::in(['Admin', 'Technician', 'Supervisor']),
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        /*
         * Prevent an administrator from accidentally deleting
         * the account currently being used.
         */
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}