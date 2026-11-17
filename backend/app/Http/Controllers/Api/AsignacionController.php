<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asignacion;
use App\Models\Cuadrante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AsignacionController extends Controller
{
    // Consultar todas las asignaciones
    public function index()
    {
        return response()->json(
            Asignacion::with([
                'cuadrante',
                'empleado',
                'horario',
                'seccion'
            ])->get(),
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

        $asignaciones = Asignacion::with([
            'cuadrante',
            'horario',
            'seccion'
        ])
            ->where('empleado_id', $usuario->empleado_id)
            ->whereHas('cuadrante', function ($consulta) {
                $consulta->where('estado', 'publicado');
            })
            ->orderBy('fecha')
            ->get();

        return response()->json($asignaciones, 200);
    }

    // Crear una asignación
    public function store(Request $request)
    {
        $datos = $this->validarAsignacion($request);

        $asignacion = Asignacion::create($datos);

        return response()->json(
            $asignacion->load([
                'cuadrante',
                'empleado',
                'horario',
                'seccion'
            ]),
            201
        );
    }

    // Consultar una asignación por su ID
    public function show(Asignacion $asignacion)
    {
        return response()->json(
            $asignacion->load([
                'cuadrante',
                'empleado',
                'horario',
                'seccion'
            ]),
            200
        );
    }

    // Modificar una asignación
    public function update(Request $request, Asignacion $asignacion)
    {
        $datos = $this->validarAsignacion($request, $asignacion);

        $asignacion->update($datos);

        return response()->json(
            $asignacion->load([
                'cuadrante',
                'empleado',
                'horario',
                'seccion'
            ]),
            200
        );
    }

    // Eliminar una asignación
    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();

        return response()->noContent();
    }

    // Validar los datos y las reglas de negocio
    private function validarAsignacion(
        Request $request,
        ?Asignacion $asignacion = null
    ): array {
        $actualizando = $asignacion !== null;

        $datos = $request->validate([
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

        // Usar los valores nuevos o los existentes
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

            $inicio = $cuadrante->fecha_inicio->copy()->startOfDay();
            $fin = $inicio->copy()->addDays(6);
            $fechaAsignacion = Carbon::createFromFormat('!Y-m-d', $fecha);

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
            $consulta = Asignacion::where('cuadrante_id', $cuadranteId)
                ->where('empleado_id', $empleadoId)
                ->where('horario_id', $horarioId)
                ->whereDate('fecha', $fecha);

            if ($actualizando) {
                $consulta->where('id', '!=', $asignacion->id);
            }

            if ($consulta->exists()) {
                throw ValidationException::withMessages([
                    'empleado_id' =>
                        'Ya existe una asignación para este empleado, fecha y horario en el cuadrante.',
                ]);
            }
        }

        return $datos;
    }
}

