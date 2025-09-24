<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePermission
{
    /**
     * Handle an incoming request requiring a permission.
     * Usage: ->middleware(['auth', 'permission:create posts'])
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();
        if (! $user || ! method_exists($user, 'hasPermission') || ! $user->hasPermission($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
