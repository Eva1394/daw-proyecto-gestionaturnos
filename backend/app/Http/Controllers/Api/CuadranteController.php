<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuadrante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CuadranteController extends Controller
{
    // Consultar todos los cuadrantes
    public function index()
    {
        return response()->json(
            Cuadrante::with(['usuario', 'asignaciones'])->get(),
            200
        );
    }

    // Crear un cuadrante semanal
    public function store(Request $request)
    {
        $datos = $this->validarCuadrante($request);

        $cuadrante = Cuadrante::create($datos);

        return response()->json(
            $cuadrante->load(['usuario', 'asignaciones']),
            201
        );
    }

    // Consultar un cuadrante por su ID
    public function show(Cuadrante $cuadrante)
    {
        return response()->json(
            $cuadrante->load(['usuario', 'asignaciones']),
            200
        );
    }

    // Modificar un cuadrante
    public function update(Request $request, Cuadrante $cuadrante)
    {
        $datos = $this->validarCuadrante($request, $cuadrante);

        $cuadrante->update($datos);

        return response()->json(
            $cuadrante->load(['usuario', 'asignaciones']),
            200
        );
    }

    // Eliminar un cuadrante
    public function destroy(Cuadrante $cuadrante)
    {
        $cuadrante->delete();

        return response()->noContent();
    }

    // Validar los datos y las reglas del cuadrante
    private function validarCuadrante(
        Request $request,
        ?Cuadrante $cuadrante = null
    ): array {
        $actualizando = $cuadrante !== null;

        $datos = $request->validate([
            'fecha_inicio' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'date_format:Y-m-d',
            ],
            'estado' => [
                'sometimes',
                'required',
                'in:borrador,publicado',
            ],
            'usuario_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);

        // Recuperar la fecha nueva o la ya guardada
        $fechaInicio = $datos['fecha_inicio']
            ?? $cuadrante?->fecha_inicio?->format('Y-m-d');

        if ($fechaInicio !== null) {
            $fecha = Carbon::createFromFormat('!Y-m-d', $fechaInicio);

            // Los cuadrantes semanales deben empezar un lunes
            if (!$fecha->isMonday()) {
                throw ValidationException::withMessages([
                    'fecha_inicio' =>
                        'La fecha de inicio del cuadrante debe ser un lunes.',
                ]);
            }

            // No pueden existir dos cuadrantes de la misma semana
            $consulta = Cuadrante::where('fecha_inicio', $fechaInicio);

            if ($actualizando) {
                $consulta->where('id', '!=', $cuadrante->id);
            }

            if ($consulta->exists()) {
                throw ValidationException::withMessages([
                    'fecha_inicio' =>
                        'Ya existe un cuadrante para esta semana.',
                ]);
            }
        }

        return $datos;
    }
}

