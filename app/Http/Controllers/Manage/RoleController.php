<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Manage\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manage.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = \App\Models\Manage\Permission::orderBy('name')->get();

        return view('manage.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
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
        $permissions = \App\Models\Manage\Permission::orderBy('name')->get();

        return view('manage.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
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
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
