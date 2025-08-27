<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'No estás autenticado.');
        }

        // Puedes adaptar esto al método que usas para verificar permisos.
        // Aquí asumimos que usas el trait HasPermissions y el método 'can'.
        if (! $user->can($permission)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
