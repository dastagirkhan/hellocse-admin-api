<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Http\Requests\Administrator\RegisterRequest;
use App\Http\Requests\Administrator\LoginRequest;
use App\Http\Resources\AdministratorResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AdministratorController extends Controller
{
    /**
     * Handles admin registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $admin = Administrator::create($request->validated());

        return response()->json([
            'message' => 'Administrator registered successfully',
            'data' => new AdministratorResource($admin),
        ], 201);
    }

    /**
     * Handles admin login and token generation.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Administrator::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => new AdministratorResource($admin),
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
