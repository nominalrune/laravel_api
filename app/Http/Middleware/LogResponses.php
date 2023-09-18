<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogResponseCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        Log::channel('response-log')->info("", [
            "code" => $response->getStatusCode(),
            "url" => $request->fullUrl(),
            "id" => $request->user()?->id,
            "ip" => $request->ip(),
            "header" => $request->header(),
        ]);

        return $response;
    }
}
