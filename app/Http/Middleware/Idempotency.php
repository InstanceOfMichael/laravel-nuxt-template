<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class Idempotency
{
    const HEADER = "Idempotency-Key";
    const EXPIRATION_IN_MINUTES = 60;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'GET' || $request->method() == 'DELETE') {
            return $next($request);
        }

        $key = $request->header(self::HEADER);
        if (!$key) {
            return $next($request);
        }
        $requestId = ($request->user()
            ? 'U'.$request->user()->id
            : 'A'.$request->user()->ip()
        ).':'.$key;

        if (Cache::has($requestId)) {
            return Cache::get($requestId);
        }

        $expiration = $request->user()
            ? 30 /* minutes */
            : 15 /* minutes */;

        $response = $next($request);
        $response->header(self::HEADER, $key);
        Cache::put($requestId, $response, $expiration);
        return $response;
    }
}
