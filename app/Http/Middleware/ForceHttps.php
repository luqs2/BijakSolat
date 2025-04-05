<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        if (env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
