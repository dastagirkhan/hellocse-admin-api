<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AuthenticateException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Unauthorized. Please log in first.',
        ], 401);
    }
}
