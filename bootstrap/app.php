<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php'

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(EnsureFrontendRequestsAreStateful::class);
        $middleware->append(\App\Http\Middleware\Authenticate::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
