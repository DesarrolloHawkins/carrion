<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // El usuario autenticado

        // Verificar si el usuario es administrador o tiene el rol adecuado
        if ($user && $user->role === 'admin') {
            return $next($request); // Continuar si es admin
        }

        return response()->json(['error' => 'Acceso denegado.'], 403); // Denegar acceso si no es admin
    }
}
