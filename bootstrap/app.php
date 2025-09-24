<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        // Usage in routes: ->middleware(['auth', 'permission:create posts'])
        if (method_exists($middleware, 'alias')) {
            // Some middleware managers expect an array mapping; provide both forms if supported.
            try {
                $middleware->alias('permission', \App\Http\Middleware\EnsurePermission::class);
            } catch (\TypeError $e) {
                // fallback: try register if alias fails
            }
        }

        if (method_exists($middleware, 'register')) {
            $middleware->register(['permission' => \App\Http\Middleware\EnsurePermission::class]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
