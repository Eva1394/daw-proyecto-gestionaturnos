<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Iniciar sesión
    public function login(Request $request)
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower($datos['email']);
        $clave = 'login:' . $email . '|' . $request->ip();

        // Limitar los intentos de inicio de sesión
        if (RateLimiter::tooManyAttempts($clave, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Demasiados intentos. Inténtalo de nuevo más tarde.',
            ])->status(429);
        }

        // Comprobar el correo y la contraseña
        if (!Auth::attempt([
            'email' => $email,
            'password' => $datos['password'],
        ])) {
            RateLimiter::hit($clave, 60);

            return response()->json([
                'type'     => 'about:blank',
                'title'    => 'No autenticado',
                'status'   => 401,
                'detail'   => 'Las credenciales introducidas no son correctas.',
                'instance' => $request->getPathInfo(),
            ], 401);
        }


        RateLimiter::clear($clave);

        $usuario = User::findOrFail(Auth::id());

        // Crear un token de acceso
        $token = $usuario->createToken(
            'gestionaturnos',
            ['*'],
            now()->addHours(2)
        )->plainTextToken;

        return response()->json([
            'mensaje' => 'Inicio de sesión correcto.',
            'usuario' => [
                'id'     => $usuario->id,
                'nombre' => $usuario->nombre,
                'email'  => $usuario->email,
                'rol'    => $usuario->rol,
            ],
            'token' => $token,
            'tipo_token' => 'Bearer',
            'expira_en' => 7200,
        ], 200);

    }

    // Consultar el usuario autenticado
    public function perfil(Request $request)
    {
        $usuario = $request->user()->load('empleado');

        return response()->json([
            'id'       => $usuario->id,
            'nombre'   => $usuario->nombre,
            'email'    => $usuario->email,
            'rol'      => $usuario->rol,
            'empleado' => $usuario->empleado,
        ], 200);
    }


    // Cerrar sesión
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.',
        ], 200);
    }
}
