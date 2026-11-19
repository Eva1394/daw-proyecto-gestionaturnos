<?php

namespace App\Services;

use App\Models\Asignacion;
use App\Models\Cuadrante;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AsignacionService
{
    private const RELACIONES = [
        'cuadrante',
        'empleado',
        'horario',
        'seccion',
    ];

    public function listar()
    {
        return Asignacion::with(self::RELACIONES)->get();
    }

    public function misAsignaciones(int $empleadoId)
    {
        return Asignacion::with([
            'cuadrante',
            'horario',
            'seccion',
        ])
            ->where('empleado_id', $empleadoId)
            ->whereHas('cuadrante', function ($consulta) {
                $consulta->where('estado', 'publicado');
            })
            ->orderBy('fecha')
            ->get();
    }

    public function consultar(Asignacion $asignacion)
    {
        return $asignacion->load(self::RELACIONES);
    }

    public function crear(array $datos)
    {
        $this->comprobarReglas($datos);

        return Asignacion::create($datos)
            ->load(self::RELACIONES);
    }

    public function actualizar(Asignacion $asignacion, array $datos)
    {
        $this->comprobarReglas($datos, $asignacion);

        $asignacion->update($datos);

        return $asignacion->load(self::RELACIONES);
    }

    public function eliminar(Asignacion $asignacion)
    {
        $asignacion->delete();
    }

    private function comprobarReglas(
        array $datos,
        ?Asignacion $asignacion = null
    ): void {
        $cuadranteId = $datos['cuadrante_id']
            ?? $asignacion?->cuadrante_id;

        $empleadoId = $datos['empleado_id']
            ?? $asignacion?->empleado_id;

        $horarioId = $datos['horario_id']
            ?? $asignacion?->horario_id;

        $fecha = $datos['fecha']
            ?? $asignacion?->fecha?->format('Y-m-d');

        // Comprobar que la fecha pertenece a la semana
        if ($cuadranteId !== null && $fecha !== null) {
            $cuadrante = Cuadrante::findOrFail($cuadranteId);

            $inicio = $cuadrante->fecha_inicio
                ->copy()
                ->startOfDay();

            $fin = $inicio->copy()->addDays(6);

            $fechaAsignacion = Carbon::createFromFormat(
                '!Y-m-d',
                $fecha
            );

            if (
                $fechaAsignacion->lt($inicio) ||
                $fechaAsignacion->gt($fin)
            ) {
                throw ValidationException::withMessages([
                    'fecha' =>
                        'La fecha debe pertenecer a la semana del cuadrante.',
                ]);
            }
        }

        // Evitar asignaciones duplicadas
        if (
            $cuadranteId !== null &&
            $empleadoId !== null &&
            $horarioId !== null &&
            $fecha !== null
        ) {
            $consulta = Asignacion::where(
                'cuadrante_id',
                $cuadranteId
            )
                ->where('empleado_id', $empleadoId)
                ->where('horario_id', $horarioId)
                ->whereDate('fecha', $fecha);

            if ($asignacion !== null) {
                $consulta->where(
                    'id',
                    '!=',
                    $asignacion->id
                );
            }

            if ($consulta->exists()) {
                throw ValidationException::withMessages([
                    'empleado_id' =>
                        'Ya existe una asignación para este empleado, fecha y horario en el cuadrante.',
                ]);
            }
        }
    }
}
