<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    // LOGIN + JWT TOKEN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (
            !$user ||
            !Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $secret = env('JWT_SECRET');

        if (!$secret || strlen($secret) < 32) {
            return response()->json([
                'message' => 'JWT secret is not configured correctly',
            ], 500);
        }

        $now = time();
        $minutes = max(
            1,
            (int) env('JWT_TTL_MINUTES', 60)
        );

        $payload = [
            'iss' => config('app.url'),
            'sub' => (string) $user->id,
            'iat' => $now,
            'exp' => $now + ($minutes * 60),
        ];

        $token = JWT::encode(
            $payload,
            $secret,
            'HS256'
        );

        return response()->json([
            'message' => 'Login successful',
            'token_type' => 'Bearer',
            'expires_in' => $minutes * 60,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    // PROTECTED ROUTE
    public function me(Request $request)
    {
        $user = $request->attributes->get(
            'authenticated_user'
        );

        return response()->json([
            'message' => 'Authenticated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    // LOGOUT
    public function logout()
    {
        return response()->json([
            'message' =>
                'Logged out. Please discard your token.',
        ]);
    }
}
