<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php'

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(EnsureFrontendRequestsAreStateful::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle AuthenticationException
        $exceptions->render(function (AuthenticationException $exception, $request) {
            return response()->json(['message' => 'Unauthorized'], 401);
        });
        // Handle MethodNotAllowedHttpException
        $exceptions->render(function (MethodNotAllowedHttpException $exception, $request) {
            return response()->json([
                'message' => 'Method not allowed. Please check the URL and HTTP method.',
                'supported_methods' => $exception->getHeaders()['Allow'] ?? [],
            ], 405);
        });
    })
    ->create();
