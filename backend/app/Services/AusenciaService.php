<?php

namespace App\Services;

use App\Models\Ausencia;
use Illuminate\Validation\ValidationException;

class AusenciaService
{
    public function listar()
    {
        return Ausencia::with('empleado')->get();
    }

    public function consultar(Ausencia $ausencia)
    {
        return $ausencia->load('empleado');
    }

    public function crear(array $datos)
    {
        $this->comprobarFechas($datos);

        return Ausencia::create($datos)->load('empleado');
    }

    public function actualizar(Ausencia $ausencia, array $datos)
    {
        $this->comprobarFechas($datos, $ausencia);

        $ausencia->update($datos);

        return $ausencia->load('empleado');
    }

    public function eliminar(Ausencia $ausencia)
    {
        $ausencia->delete();
    }

    private function comprobarFechas(
        array $datos,
        ?Ausencia $ausencia = null
    ): void {
        $fechaInicio = $datos['fecha_inicio']
            ?? $ausencia?->fecha_inicio?->format('Y-m-d');

        $fechaFin = $datos['fecha_fin']
            ?? $ausencia?->fecha_fin?->format('Y-m-d');

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
    }
}
