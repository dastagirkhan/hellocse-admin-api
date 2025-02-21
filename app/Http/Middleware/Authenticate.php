<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\AuthenticateException;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticateException();
    }
}
