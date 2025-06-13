<?php

namespace Modules\AVICONTROL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class BypassPermissionMiddleware
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
        // Log the request for debugging
        Log::info('BypassPermissionMiddleware: Processing request', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user' => auth()->check() ? auth()->user()->id : 'Guest'
        ]);
        
        // Bypass permission checks using Gate
        if (auth()->check()) {
            $user = auth()->user();
            
            // Define a catch-all gate that always returns true
            Gate::before(function ($user, $ability) {
                Log::info("Bypassing permission check for: " . $ability);
                return true; // Allow everything
            });
            
            Log::info('BypassPermissionMiddleware: Permissions bypassed for user ' . $user->id);
        }
        
        return $next($request);
    }
}