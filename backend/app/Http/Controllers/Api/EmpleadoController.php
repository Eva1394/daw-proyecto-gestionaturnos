<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Services\EmpleadoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function __construct(
        private EmpleadoService $empleadoService
    ) {
    }

    // Consultar todos los empleados y sus secciones
    public function index()
    {
        return response()->json(
            $this->empleadoService->listar(),
            200
        );
    }

    // Crear un empleado
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:150',
            'seccion_id' => 'required|integer|exists:secciones,id',
            'tipo_jornada' => ['required', Rule::in(['completa', 'parcial'])],
            'tipo_turno' => ['required', Rule::in(['fijo', 'rotativo'])],
            'activo' => 'sometimes|boolean',
        ]);

        $empleado = $this->empleadoService->crear($datos);

        return response()->json($empleado, 201);
    }

    // Consultar un empleado por su ID
    public function show(Empleado $empleado)
    {
        return response()->json(
            $this->empleadoService->consultar($empleado),
            200
        );
    }

    // Modificar un empleado
    public function update(Request $request, Empleado $empleado)
    {
        $datos = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'apellidos' => 'sometimes|required|string|max:150',
            'seccion_id' => 'sometimes|required|integer|exists:secciones,id',
            'tipo_jornada' => ['sometimes', 'required', Rule::in(['completa', 'parcial'])],
            'tipo_turno' => ['sometimes', 'required', Rule::in(['fijo', 'rotativo'])],
            'activo' => 'sometimes|boolean',
        ]);

        $empleado = $this->empleadoService->actualizar($empleado, $datos);

        return response()->json($empleado, 200);
    }

    // Eliminar un empleado
    public function destroy(Empleado $empleado)
    {
        $this->empleadoService->eliminar($empleado);

        return response()->noContent();
    }
}
