<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConfiguracionController extends Controller
{
    // Consultar todas las configuraciones
    public function index()
    {
        return response()->json(
            Configuracion::with(['seccion', 'horario'])->get(),
            200
        );
    }

    // Crear una configuración
    public function store(Request $request)
    {
        $datos = $this->validarConfiguracion($request);

        $configuracion = Configuracion::create($datos);

        return response()->json(
            $configuracion->load(['seccion', 'horario']),
            201
        );
    }

    // Consultar una configuración por su ID
    public function show(Configuracion $configuracion)
    {
        return response()->json(
            $configuracion->load(['seccion', 'horario']),
            200
        );
    }

    // Modificar una configuración
    public function update(Request $request, Configuracion $configuracion)
    {
        $datos = $this->validarConfiguracion($request, $configuracion);

        $configuracion->update($datos);

        return response()->json(
            $configuracion->load(['seccion', 'horario']),
            200
        );
    }

    // Eliminar una configuración
    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();

        return response()->noContent();
    }

    // Comprobar los datos y las reglas de negocio
    private function validarConfiguracion(
        Request $request,
        ?Configuracion $configuracion = null
    ): array {
        $actualizando = $configuracion !== null;

        $reglas = [
            'seccion_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:secciones,id',
            ],
            'horario_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:horarios,id',
            ],
            'minimo_empleados' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'min:0',
            ],
            'maximo_empleados' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'min:0',
            ],
        ];

        $datos = $request->validate($reglas);

        // Utilizar los valores nuevos o los ya guardados
        $seccionId = $datos['seccion_id']
            ?? $configuracion?->seccion_id;

        $horarioId = $datos['horario_id']
            ?? $configuracion?->horario_id;

        $minimo = $datos['minimo_empleados']
            ?? $configuracion?->minimo_empleados;

        $maximo = $datos['maximo_empleados']
            ?? $configuracion?->maximo_empleados;

        // El máximo nunca puede ser inferior al mínimo
        if ($minimo !== null && $maximo !== null && $maximo < $minimo) {
            throw ValidationException::withMessages([
                'maximo_empleados' =>
                    'El máximo de empleados debe ser igual o superior al mínimo.',
            ]);
        }

        // No puede repetirse la combinación sección + horario
        if ($seccionId !== null && $horarioId !== null) {
            $consulta = Configuracion::where('seccion_id', $seccionId)
                ->where('horario_id', $horarioId);

            if ($actualizando) {
                $consulta->where('id', '!=', $configuracion->id);
            }

            if ($consulta->exists()) {
                throw ValidationException::withMessages([
                    'horario_id' =>
                        'Ya existe una configuración para esta sección y horario.',
                ]);
            }
        }

        return $datos;
    }
}

