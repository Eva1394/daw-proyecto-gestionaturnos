<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ausencia;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AusenciaController extends Controller
{
    // Consultar todas las ausencias
    public function index()
    {
        return response()->json(
            Ausencia::with('empleado')->get(),
            200
        );
    }

    // Registrar una ausencia
    public function store(Request $request)
    {
        $datos = $this->validarAusencia($request);

        $ausencia = Ausencia::create($datos);

        return response()->json(
            $ausencia->load('empleado'),
            201
        );
    }

    // Consultar una ausencia por su ID
    public function show(Ausencia $ausencia)
    {
        return response()->json(
            $ausencia->load('empleado'),
            200
        );
    }

    // Modificar una ausencia
    public function update(Request $request, Ausencia $ausencia)
    {
        $datos = $this->validarAusencia($request, $ausencia);

        $ausencia->update($datos);

        return response()->json(
            $ausencia->load('empleado'),
            200
        );
    }

    // Eliminar una ausencia
    public function destroy(Ausencia $ausencia)
    {
        $ausencia->delete();

        return response()->noContent();
    }

    // Validar los datos de una ausencia
    private function validarAusencia(
        Request $request,
        ?Ausencia $ausencia = null
    ): array {
        $actualizando = $ausencia !== null;

        $datos = $request->validate([
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

        // Recuperar las fechas nuevas o las ya guardadas
        $fechaInicio = $datos['fecha_inicio']
            ?? $ausencia?->fecha_inicio?->format('Y-m-d');

        $fechaFin = $datos['fecha_fin']
            ?? $ausencia?->fecha_fin?->format('Y-m-d');

        // La ausencia no puede terminar antes de empezar
        if (
            $fechaInicio !== null &&
            $fechaFin !== null &&
            $fechaFin < $fechaInicio
        ) {
            throw ValidationException::withMessages([
                'fecha_fin' =>
                    'La fecha de fin no puede ser anterior a la fecha de inicio.',
            ]);
        }

        return $datos;
    }
}
