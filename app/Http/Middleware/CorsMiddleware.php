<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', "http://localhost:3000")
            ->header('Access-Control-Allow-Methods', "GET, POST, PUT, DELETE")
            ->header('Access-Control-Allow-Headers', "Accept, Content-Type, Authorization, x-csrf-token")
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
