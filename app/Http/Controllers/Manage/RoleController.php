<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Manage\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    private function ensurePermission($request, string $permission): void
    {
        $user = $request->user();
        if (! $user || ! method_exists($user, 'hasPermission') || ! $user->hasPermission($permission)) {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensurePermission(request(), 'manage roles');
        $roles = Role::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manage.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->ensurePermission(request(), 'manage roles');
        $permissions = \App\Models\Manage\Permission::orderBy('name')->get();

        return view('manage.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'manage roles');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(Role::class)],
            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        $role = Role::create($validated);

        // sync permissions if provided
        $permissions = $request->input('permissions', []);
        if (!empty($permissions)) {
            $role->permissions()->sync($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $this->ensurePermission(request(), 'manage roles');
        $permissions = \App\Models\Manage\Permission::orderBy('name')->get();

        return view('manage.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->ensurePermission($request, 'manage roles');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(Role::class)->ignore($role->id)],
            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        $role->update($validated);

        // sync permissions
        $permissions = $request->input('permissions', []);
        $role->permissions()->sync($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->ensurePermission(request(), 'manage roles');
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
