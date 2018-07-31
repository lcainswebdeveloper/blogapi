<?php

namespace App\Http\Middleware;

use Closure;

class AddCorsHeaders
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
        if ($request->server('HTTP_ORIGIN')) {
            $origin = $request->server('HTTP_ORIGIN');
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Headers: Origin, Content-Type');
        }
        return $next($request);
    }
}
