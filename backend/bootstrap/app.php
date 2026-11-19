<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\UniqueConstraintViolationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'rol' => \App\Http\Middleware\ComprobarRol::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return null;
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });
        $exceptions->render(function (\Throwable $e, Request $request) {

            if (!$request->is('api/*')) {
                return null;
            }

            $status = 500;
            $title = 'Error interno del servidor';
            $detail = 'Se ha producido un error inesperado.';
            $errors = [];

            if ($e instanceof ValidationException) {
                $status = $e->status;
                $title = 'Error de validación';
                $detail = 'Los datos enviados no son válidos.';

                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $errors[] = [
                            'field' => $field,
                            'message' => $message,
                        ];
                    }
                }
            } elseif ($e instanceof AuthenticationException) {
                $status = 401;
                $title = 'No autenticado';
                $detail = 'Debes iniciar sesión para acceder a este recurso.';
            } elseif ($e instanceof AuthorizationException) {
                $status = 403;
                $title = 'Acceso denegado';
                $detail = 'No tienes permisos para realizar esta operación.';
            } elseif ($e instanceof UniqueConstraintViolationException) {
                $status = 409;
                $title = 'Conflicto';
                $detail = 'Ya existe un registro con esos datos.';
            } elseif ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();

                $title = match ($status) {
                    400 => 'Solicitud incorrecta',
                    401 => 'No autenticado',
                    403 => 'Acceso denegado',
                    404 => 'Recurso no encontrado',
                    405 => 'Método no permitido',
                    409 => 'Conflicto',
                    422 => 'Error de validación',
                    429 => 'Demasiadas solicitudes',
                    default => 'Error HTTP',
                };

                $detail = match ($status) {
                    404 => 'El recurso solicitado no existe.',
                    405 => 'El método HTTP no está permitido.',
                    429 => 'Has realizado demasiadas solicitudes.',
                    default => $e->getMessage() ?: 'No se pudo completar la solicitud.',
                };
            }

            $problem = [
                'type' => 'about:blank',
                'title' => $title,
                'status' => $status,
                'detail' => $detail,
                'instance' => $request->getPathInfo(),
                'timestamp' => now()->toIso8601String(),
            ];

            if (!empty($errors)) {
                $problem['errors'] = $errors;
            }

            return response()->json(
                $problem,
                $status,
                ['Content-Type' => 'application/problem+json']
            );
        });

    })->create();
