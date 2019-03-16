<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class Transaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return DB::transaction(function () use ($request, $next) {
            return $next($request);
        });
    }
}
