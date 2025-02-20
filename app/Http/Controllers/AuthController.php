<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Handles admin registration.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function register(AuthRequest $request): JsonResponse
    {
        $admin = Administrator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Administrator registered successfully',
            'data' => $admin,
        ], 201);
    }


    /**
     * Handles admin login and token generation.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Administrator::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
