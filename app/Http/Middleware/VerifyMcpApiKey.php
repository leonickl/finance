<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMcpApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.mcp.api_key');

        if ($apiKey === null || trim($apiKey) === '') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $authorization = $request->bearerToken();

        if ($authorization === null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! hash_equals($apiKey, $authorization)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
