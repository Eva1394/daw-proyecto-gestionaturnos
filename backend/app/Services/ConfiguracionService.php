<?php

namespace App\Services;

use App\Models\Configuracion;
use Illuminate\Validation\ValidationException;

class ConfiguracionService
{
    public function listar()
    {
        return Configuracion::with(['seccion', 'horario'])->get();
    }

    public function consultar(Configuracion $configuracion)
    {
        return $configuracion->load(['seccion', 'horario']);
    }

    public function crear(array $datos)
    {
        $this->comprobarReglas($datos);

        return Configuracion::create($datos)
            ->load(['seccion', 'horario']);
    }

    public function actualizar(Configuracion $configuracion, array $datos)
    {
        $this->comprobarReglas($datos, $configuracion);

        $configuracion->update($datos);

        return $configuracion->load(['seccion', 'horario']);
    }

    public function eliminar(Configuracion $configuracion)
    {
        $configuracion->delete();
    }

    private function comprobarReglas(
        array $datos,
        ?Configuracion $configuracion = null
    ): void {
        $seccionId = $datos['seccion_id']
            ?? $configuracion?->seccion_id;

        $horarioId = $datos['horario_id']
            ?? $configuracion?->horario_id;

        $minimo = $datos['minimo_empleados']
            ?? $configuracion?->minimo_empleados;

        $maximo = $datos['maximo_empleados']
            ?? $configuracion?->maximo_empleados;

        if ($minimo !== null && $maximo !== null && $maximo < $minimo) {
            throw ValidationException::withMessages([
                'maximo_empleados' =>
                    'El máximo de empleados debe ser igual o superior al mínimo.',
            ]);
        }

        if ($seccionId !== null && $horarioId !== null) {
            $consulta = Configuracion::where('seccion_id', $seccionId)
                ->where('horario_id', $horarioId);

            if ($configuracion !== null) {
                $consulta->where('id', '!=', $configuracion->id);
            }

            if ($consulta->exists()) {
                throw ValidationException::withMessages([
                    'horario_id' =>
                        'Ya existe una configuración para esta sección y horario.',
                ]);
            }
        }
    }
}
