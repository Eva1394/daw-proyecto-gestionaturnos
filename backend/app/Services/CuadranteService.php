<?php

namespace App\Services;

use App\Models\Cuadrante;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CuadranteService
{
    public function listar()
    {
        return Cuadrante::with(['usuario', 'asignaciones'])->get();
    }

    public function consultar(Cuadrante $cuadrante)
    {
        return $cuadrante->load(['usuario', 'asignaciones']);
    }

    public function crear(array $datos)
    {
        $this->comprobarReglas($datos);

        return Cuadrante::create($datos)
            ->load(['usuario', 'asignaciones']);
    }

    public function actualizar(Cuadrante $cuadrante, array $datos)
    {
        $this->comprobarReglas($datos, $cuadrante);

        $cuadrante->update($datos);

        return $cuadrante->load(['usuario', 'asignaciones']);
    }

    public function eliminar(Cuadrante $cuadrante)
    {
        $cuadrante->delete();
    }

    private function comprobarReglas(
        array $datos,
        ?Cuadrante $cuadrante = null
    ): void {
        $fechaInicio = $datos['fecha_inicio']
            ?? $cuadrante?->fecha_inicio?->format('Y-m-d');

        if ($fechaInicio === null) {
            return;
        }

        $fecha = Carbon::createFromFormat('!Y-m-d', $fechaInicio);

        // Los cuadrantes semanales deben empezar un lunes
        if (!$fecha->isMonday()) {
            throw ValidationException::withMessages([
                'fecha_inicio' =>
                    'La fecha de inicio del cuadrante debe ser un lunes.',
            ]);
        }

        // No puede repetirse la misma semana
        $consulta = Cuadrante::where('fecha_inicio', $fechaInicio);

        if ($cuadrante !== null) {
            $consulta->where('id', '!=', $cuadrante->id);
        }

        if ($consulta->exists()) {
            throw ValidationException::withMessages([
                'fecha_inicio' =>
                    'Ya existe un cuadrante para esta semana.',
            ]);
        }
    }
}
