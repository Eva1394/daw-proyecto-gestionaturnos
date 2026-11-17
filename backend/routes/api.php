<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SeccionController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\HorarioController;
use App\Http\Controllers\Api\ConfiguracionController;
use App\Http\Controllers\Api\AusenciaController;
use App\Http\Controllers\Api\CuadranteController;
use App\Http\Controllers\Api\AsignacionController;
use App\Http\Controllers\Api\AuthController;

// Inicio de sesión: acceso público
Route::post('/login', [AuthController::class, 'login']);

// Todas las rutas siguientes requieren autenticación
Route::middleware('auth:sanctum')->group(function () {

    // Perfil y cierre de sesión
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Consultar los turnos del empleado autenticado
    Route::get('/mis-asignaciones', [AsignacionController::class, 'misAsignaciones'])
        ->middleware('rol:empleado');

    // Consultas permitidas a administradores y empleados
    Route::middleware('rol:admin')->group(function () {

        Route::get('secciones', [SeccionController::class, 'index']);
        Route::get('secciones/{seccion}', [SeccionController::class, 'show']);

        Route::get('empleados', [EmpleadoController::class, 'index']);
        Route::get('empleados/{empleado}', [EmpleadoController::class, 'show']);

        Route::get('horarios', [HorarioController::class, 'index']);
        Route::get('horarios/{horario}', [HorarioController::class, 'show']);

        Route::get('configuraciones', [ConfiguracionController::class, 'index']);
        Route::get('configuraciones/{configuracion}', [ConfiguracionController::class, 'show']);

        Route::get('ausencias', [AusenciaController::class, 'index']);
        Route::get('ausencias/{ausencia}', [AusenciaController::class, 'show']);

        Route::get('cuadrantes', [CuadranteController::class, 'index']);
        Route::get('cuadrantes/{cuadrante}', [CuadranteController::class, 'show']);

        Route::get('asignaciones', [AsignacionController::class, 'index']);
        Route::get('asignaciones/{asignacion}', [AsignacionController::class, 'show']);
    });

    // Operaciones reservadas al administrador
    Route::middleware('rol:admin')->group(function () {

        Route::post('secciones', [SeccionController::class, 'store']);
        Route::match(['put', 'patch'], 'secciones/{seccion}', [SeccionController::class, 'update']);
        Route::delete('secciones/{seccion}', [SeccionController::class, 'destroy']);

        Route::post('empleados', [EmpleadoController::class, 'store']);
        Route::match(['put', 'patch'], 'empleados/{empleado}', [EmpleadoController::class, 'update']);
        Route::delete('empleados/{empleado}', [EmpleadoController::class, 'destroy']);

        Route::post('horarios', [HorarioController::class, 'store']);
        Route::match(['put', 'patch'], 'horarios/{horario}', [HorarioController::class, 'update']);
        Route::delete('horarios/{horario}', [HorarioController::class, 'destroy']);

        Route::post('configuraciones', [ConfiguracionController::class, 'store']);
        Route::match(['put', 'patch'], 'configuraciones/{configuracion}', [ConfiguracionController::class, 'update']);
        Route::delete('configuraciones/{configuracion}', [ConfiguracionController::class, 'destroy']);

        Route::post('ausencias', [AusenciaController::class, 'store']);
        Route::match(['put', 'patch'], 'ausencias/{ausencia}', [AusenciaController::class, 'update']);
        Route::delete('ausencias/{ausencia}', [AusenciaController::class, 'destroy']);

        Route::post('cuadrantes', [CuadranteController::class, 'store']);
        Route::match(['put', 'patch'], 'cuadrantes/{cuadrante}', [CuadranteController::class, 'update']);
        Route::delete('cuadrantes/{cuadrante}', [CuadranteController::class, 'destroy']);

        Route::post('asignaciones', [AsignacionController::class, 'store']);
        Route::match(['put', 'patch'], 'asignaciones/{asignacion}', [AsignacionController::class, 'update']);
        Route::delete('asignaciones/{asignacion}', [AsignacionController::class, 'destroy']);
    });
});


