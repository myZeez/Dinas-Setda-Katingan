<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'track.visitor' => \App\Http\Middleware\TrackVisitor::class,
            'token.valid' => \App\Http\Middleware\EnsureTokenIsValid::class,
        ]);

        // Add visitor tracking to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
