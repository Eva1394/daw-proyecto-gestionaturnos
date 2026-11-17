<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComprobarRol
{
    /**
     * Comprobar que el usuario tiene uno de los roles permitidos.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        // Comprobar que existe un usuario autenticado
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'No autenticado.',
            ], 401);
        }

        // Comprobar si el rol está autorizado
        if (!in_array($usuario->rol, $roles, true)) {
            return response()->json([
                'mensaje' => 'No tienes permisos para realizar esta operación.',
            ], 403);
        }

        // Permitir continuar con la petición
        return $next($request);
    }
}
