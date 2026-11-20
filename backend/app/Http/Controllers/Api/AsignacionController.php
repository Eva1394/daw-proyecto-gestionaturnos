<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Services\AsignacionService;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function __construct(
        private AsignacionService $asignacionService
    ) {
    }

    // Consultar todas las asignaciones
    public function index()
    {
        return response()->json(
            $this->asignacionService->listar(),
            200
        );
    }

    // Consultar las asignaciones del empleado autenticado
    public function misAsignaciones(Request $request)
    {
        $usuario = $request->user();

        // El administrador no tiene un empleado asociado
        if ($usuario->empleado_id === null) {
            return response()->json([
                'mensaje' => 'El usuario no tiene un empleado asociado.',
            ], 403);
        }

        $asignaciones = $this->asignacionService->misAsignaciones(
            $usuario->empleado_id
        );

        return response()->json($asignaciones, 200);
    }

    // Crear una asignación
    public function store(Request $request)
    {
        $datos = $this->validarAsignacion($request);

        $asignacion = $this->asignacionService->crear($datos);

        return response()->json($asignacion, 201);
    }

    // Consultar una asignación por su ID
    public function show(Asignacion $asignacion)
    {
        return response()->json(
            $this->asignacionService->consultar($asignacion),
            200
        );
    }

    // Modificar una asignación
    public function update(Request $request, Asignacion $asignacion)
    {
        $datos = $this->validarAsignacion($request, true);

        $asignacion = $this->asignacionService->actualizar(
            $asignacion,
            $datos
        );

        return response()->json($asignacion, 200);
    }

    // Eliminar una asignación
    public function destroy(Asignacion $asignacion)
    {
        $this->asignacionService->eliminar($asignacion);

        return response()->noContent();
    }

    // Validar los datos recibidos
    private function validarAsignacion(
        Request $request,
        bool $actualizando = false
    ): array {
        return $request->validate([
            'cuadrante_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:cuadrantes,id',
            ],
            'empleado_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:empleados,id',
            ],
            'horario_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:horarios,id',
            ],
            'seccion_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:secciones,id',
            ],
            'fecha' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'date_format:Y-m-d',
            ],
            'horas' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:99.99',
            ],
            'estado' => [
                'sometimes',
                'required',
                'in:prevista,confirmada,cancelada',
            ],
        ]);
    }
}
