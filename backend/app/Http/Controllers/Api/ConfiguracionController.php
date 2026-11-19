<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Services\ConfiguracionService;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function __construct(
        private ConfiguracionService $configuracionService
    ) {
    }

    // Consultar todas las configuraciones
    public function index()
    {
        return response()->json(
            $this->configuracionService->listar(),
            200
        );
    }

    // Crear una configuración
    public function store(Request $request)
    {
        $datos = $this->validarConfiguracion($request);

        $configuracion = $this->configuracionService->crear($datos);

        return response()->json($configuracion, 201);
    }

    // Consultar una configuración por su ID
    public function show(Configuracion $configuracion)
    {
        return response()->json(
            $this->configuracionService->consultar($configuracion),
            200
        );
    }

    // Modificar una configuración
    public function update(Request $request, Configuracion $configuracion)
    {
        $datos = $this->validarConfiguracion($request, true);

        $configuracion = $this->configuracionService->actualizar(
            $configuracion,
            $datos
        );

        return response()->json($configuracion, 200);
    }

    // Eliminar una configuración
    public function destroy(Configuracion $configuracion)
    {
        $this->configuracionService->eliminar($configuracion);

        return response()->noContent();
    }

    // Validar los datos recibidos
    private function validarConfiguracion(
        Request $request,
        bool $actualizando = false
    ): array {
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

        return $request->validate($reglas);
    }
}
