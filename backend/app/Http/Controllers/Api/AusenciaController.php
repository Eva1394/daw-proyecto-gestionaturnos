<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ausencia;
use App\Services\AusenciaService;
use Illuminate\Http\Request;

class AusenciaController extends Controller
{
    public function __construct(
        private AusenciaService $ausenciaService
    ) {
    }

    // Consultar todas las ausencias
    public function index()
    {
        return response()->json(
            $this->ausenciaService->listar(),
            200
        );
    }

    // Registrar una ausencia
    public function store(Request $request)
    {
        $datos = $this->validarAusencia($request);

        $ausencia = $this->ausenciaService->crear($datos);

        return response()->json($ausencia, 201);
    }

    // Consultar una ausencia por su ID
    public function show(Ausencia $ausencia)
    {
        return response()->json(
            $this->ausenciaService->consultar($ausencia),
            200
        );
    }

    // Modificar una ausencia
    public function update(Request $request, Ausencia $ausencia)
    {
        $datos = $this->validarAusencia($request, true);

        $ausencia = $this->ausenciaService->actualizar(
            $ausencia,
            $datos
        );

        return response()->json($ausencia, 200);
    }

    // Eliminar una ausencia
    public function destroy(Ausencia $ausencia)
    {
        $this->ausenciaService->eliminar($ausencia);

        return response()->noContent();
    }

    // Validar los datos recibidos
    private function validarAusencia(
        Request $request,
        bool $actualizando = false
    ): array {
        return $request->validate([
            'empleado_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:empleados,id',
            ],
            'tipo' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'in:vacaciones,baja,otro',
            ],
            'fecha_inicio' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'date_format:Y-m-d',
            ],
            'fecha_fin' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'date_format:Y-m-d',
            ],
            'observaciones' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000',
            ],
        ]);
    }
}
