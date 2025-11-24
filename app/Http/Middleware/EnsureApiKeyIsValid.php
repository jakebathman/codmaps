<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.api_key');
        $providedKey = (string) $request->header('X-API-KEY');

        abort_if(empty($apiKey) || ! hash_equals($apiKey, $providedKey), Response::HTTP_UNAUTHORIZED);

        return $next($request);
    }
}
