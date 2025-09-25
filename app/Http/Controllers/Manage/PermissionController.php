<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Manage\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
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
        $this->ensurePermission(request(), 'manage permissions');
        $permissions = Permission::orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('manage.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $this->ensurePermission(request(), 'manage permissions');
        return view('manage.permissions.create');
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'manage permissions');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(Permission::class)],
            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        $this->ensurePermission(request(), 'manage permissions');
        return view('manage.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $this->ensurePermission($request, 'manage permissions');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(Permission::class)->ignore($permission->id)],
            'guard_name' => ['nullable', 'string', 'max:255'],
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $this->ensurePermission(request(), 'manage permissions');
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
