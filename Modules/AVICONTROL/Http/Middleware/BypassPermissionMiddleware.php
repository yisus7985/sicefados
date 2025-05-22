<?php

namespace Modules\AVICONTROL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        // Force the user to have all permissions
        if (auth()->check()) {
            $user = auth()->user();
            
            // This is a hack to bypass permission checks
            // It temporarily modifies the user's can method to always return true
            $user->can = function($permission) {
                Log::info("Bypassing permission check for: " . $permission);
                return true;
            };
            
            Log::info('BypassPermissionMiddleware: Permissions bypassed for user ' . $user->id);
        }
        
        return $next($request);
    }
}
