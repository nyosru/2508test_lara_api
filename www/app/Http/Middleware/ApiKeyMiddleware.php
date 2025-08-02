<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Попытка получить ключ из заголовка (например, X-API-KEY)
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            ApiKey::where('key', $apiKey)->firstOrFail();
            // Ключ есть, пропускаем запрос дальше
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

    }
}
