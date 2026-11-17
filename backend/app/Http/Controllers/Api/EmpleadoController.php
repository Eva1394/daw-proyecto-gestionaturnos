<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    // Consultar todos los empleados y sus secciones
    public function index()
    {
        return response()->json(
            Empleado::with('seccion')->get(),
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

        $empleado = Empleado::create($datos);

        return response()->json(
            $empleado->load('seccion'),
            201
        );
    }

    // Consultar un empleado por su ID
    public function show(Empleado $empleado)
    {
        return response()->json(
            $empleado->load('seccion'),
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

        $empleado->update($datos);

        return response()->json(
            $empleado->load('seccion'),
            200
        );
    }

    // Eliminar un empleado
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return response()->noContent();
    }
}
