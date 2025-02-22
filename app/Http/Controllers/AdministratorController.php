<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Http\Requests\Administrator\RegisterRequest;
use App\Http\Requests\Administrator\LoginRequest;
use App\Http\Resources\AdministratorResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdministratorController extends Controller
{
    /**
     * Handles admin registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $admin = Administrator::create($request->validated());

            DB::commit();

            return response()->json([
                'message' => 'Administrator registered successfully',
                'data' => new AdministratorResource($admin),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Administrator registration failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to register administrator.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handles admin login and token generation.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Get rate limit settings from config
        $maxAttempts = config('administrator.login.max_attempts');
        $decayMinutes = config('administrator.login.decay_minutes');

        // Define a unique key for rate limiting (e.g., based on IP address)
        $key = 'login:' . $request->ip();

        // Check if the rate limit is exceeded
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            // Calculate the number of seconds until the next attempt is allowed
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
            ], 429); // 429 = Too Many Requests
        }

        // Attempt to find the admin and validate credentials
        $admin = Administrator::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Generate a token for the authenticated admin
            $token = $admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => new AdministratorResource($admin),
                'token' => $token,
            ], 200);
        }

        // Increment the rate limiter on failed attempts
        RateLimiter::hit($key, $decayMinutes * 60); // Convert minutes to seconds

        // Log the failed login attempt
        Log::warning('Failed login attempt for email: ' . $request->email);

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
