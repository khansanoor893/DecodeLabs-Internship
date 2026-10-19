<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class JwtAuthMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $header = $request->header('Authorization');

        if (
            !$header ||
            !preg_match(
                '/^Bearer\s+(\S+)$/i',
                $header,
                $matches
            )
        ) {
            return response()->json([
                'message' => 'Bearer token required',
            ], 401);
        }

        $secret = env('JWT_SECRET');

        if (!$secret || strlen($secret) < 32) {
            return response()->json([
                'message' => 'JWT secret is not configured',
            ], 500);
        }

        try {
            $payload = JWT::decode(
                $matches[1],
                new Key($secret, 'HS256')
            );

            $user = User::find($payload->sub ?? null);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found',
                ], 401);
            }

            $request->attributes->set(
                'authenticated_user',
                $user
            );

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Token is invalid or expired',
            ], 401);
        }

        return $next($request);
    }
}
