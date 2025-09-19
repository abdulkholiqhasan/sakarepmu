<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $q = request('q');

        $users = User::with('roles')
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('id', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manage.users.index', compact('users'));
    }

    public function create()
    {
        $roles = \App\Models\Manage\Role::orderBy('name')->get();

        return view('manage.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Normalize username to lowercase before validation
        $request->merge(['username' => strtolower((string) $request->input('username', ''))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique(User::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Extra: ensure the email domain appears to accept mail (has MX record).
        // Skip this check in the testing environment to avoid CI/network dependency.
        if (! app()->environment('testing')) {
            $email = $validated['email'] ?? '';
            $domain = str_contains($email, '@') ? explode('@', $email, 2)[1] : null;
            if (! $domain || ! checkdnsrr($domain, 'MX')) {
                return back()->withErrors(['email' => 'The email address does not appear to be a deliverable address.'])->withInput();
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // roles can be provided as array of role ids
        $roles = $request->input('roles', []);
        if (!empty($roles)) {
            $user->roles()->sync($roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = \App\Models\Manage\Role::orderBy('name')->get();

        return view('manage.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // If email changed (case-insensitive), ensure the domain accepts mail (MX)
        if (isset($validated['email']) && strtolower($validated['email']) !== strtolower($user->email ?? '')) {
            if (! app()->environment('testing')) {
                $email = $validated['email'];
                $domain = str_contains($email, '@') ? explode('@', $email, 2)[1] : null;
                if (! $domain || ! checkdnsrr($domain, 'MX')) {
                    return back()->withErrors(['email' => 'The email address does not appear to be a deliverable address.'])->withInput();
                }
            }

            // mark as unverified when changed
            $validated['email_verified_at'] = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // sync roles
        $roles = $request->input('roles', []);
        $user->roles()->sync($roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
