<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    /**
     * Handle an incoming request from franchise / child systems.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract API Key from header or query
        $apiKey = $request->header('X-API-KEY')
            ?? $request->bearerToken()
            ?? $request->input('api_key');

        if (empty($apiKey)) {
            return response()->json([
                'status' => 'FAILED',
                'response_code' => '401',
                'message' => 'Unauthorized: Missing API Key in request headers (X-API-KEY or Bearer token required).',
            ], 401);
        }

        $franchise = User::where('api_key', $apiKey)->first();

        if (!$franchise) {
            return response()->json([
                'status' => 'FAILED',
                'response_code' => '401',
                'message' => 'Unauthorized: Invalid API Key.',
            ], 401);
        }

        if ($franchise->status !== 'active') {
            return response()->json([
                'status' => 'FAILED',
                'response_code' => '403',
                'message' => 'Forbidden: Franchise account is ' . $franchise->status . '. Please contact administration.',
            ], 403);
        }

        // Set user on request
        $request->setUserResolver(fn () => $franchise);
        $request->attributes->set('franchise', $franchise);

        return $next($request);
    }
}
