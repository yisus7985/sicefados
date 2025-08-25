<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class LangMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Establecer el idioma si está disponible en la sesión
        if (!empty(session('lang'))) {
            App::setLocale(session('lang'));
        }

        // Verificar el nombre de la ruta y el acceso a la autorización
        if ($request->route()->getName() !== '' && strpos($request->route()->getName(), 'cefa.') !== 0) {
            // Obtener la acción de la ruta
            $action = $request->route()->getAction();

            if (is_string($action['uses'])) {
                $pos2 = strpos($action['uses'], 'Auth');
                if ($pos2 === false) {
                    // Excluir rutas de AVICONTROL de la verificación de autorización
                    if (strpos($request->route()->getName(), 'avicontrol.') === 0) {
                        // Log para debugging
                        \Log::info('AVICONTROL route accessed: ' . $request->route()->getName());
                        // Permitir acceso a rutas de AVICONTROL sin verificación
                        return $next($request);
                    }
                    
                    // Permitir acceso específico a movimientos
                    if (strpos($request->getPathInfo(), '/avicontrol/admin/inventory/movements') !== false) {
                        \Log::info('AVICONTROL movements route bypassed');
                        return $next($request);
                    }
                    // Si no contiene 'Auth', entonces verificar el acceso usando Gate
                    Gate::authorize('haveaccess', $request->route()->getName());
                }
            }
        }

        return $next($request);
    }
}
