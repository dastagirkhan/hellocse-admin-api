<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Customize the response for unauthenticated users
        $this->app->singleton(
            \Illuminate\Auth\Middleware\Authenticate::class,
            function ($app) {
                return new class($app['auth']) extends \Illuminate\Auth\Middleware\Authenticate {
                    protected function unauthenticated($request, array $guards)
                    {
                        if ($request->expectsJson()) {
                            return response()->json(['message' => 'Unauthorized'], 401);
                        }

                        throw new AuthenticationException('Unauthorized.', $guards);
                    }
                };
            }
        );
    }
}
