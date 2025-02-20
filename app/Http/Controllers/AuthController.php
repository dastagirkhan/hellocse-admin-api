<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handles admin registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email|unique:administrators',
            'password' => 'required|min:8',
        ]);

        // Create new administrator
        $admin = Administrator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before storing
        ]);

        // Return response with created administrator data
        return response()->json($admin, 201);
    }

    /**
     * Handles admin login and token generation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Retrieve administrator by email
        $admin = Administrator::where('email', $request->email)->first();

        // Debugging statement (commented out)
        // dd($admin->count());

        // Check if admin exists and password is correct
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Generate API token
            $token = $admin->createToken('API Token')->plainTextToken;

            // Return token in response
            return response()->json(['token' => $token], 200);
        }

        // Debugging statement (commented out)
        // dd("hello");

        // Return error response for invalid credentials
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
