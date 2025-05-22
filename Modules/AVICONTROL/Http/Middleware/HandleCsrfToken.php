<?php

namespace Modules\AVICONTROL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandleCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure CSRF token is available for all views
        view()->share('csrf_token', csrf_token());
        
        // Log the request for debugging
        if ($request->isMethod('post')) {
            Log::info('POST request received', [
                'url' => $request->fullUrl(),
                'has_token' => $request->hasHeader('X-CSRF-TOKEN'),
                'token' => $request->header('X-CSRF-TOKEN'),
                'session_token' => session()->token(),
            ]);
        }
        
        return $next($request);
    }
}
