<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeccionController extends Controller
{
    // Consultar todas las secciones
    public function index()
    {
        return response()->json(Seccion::all(), 200);
    }

    // Crear una sección
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100|unique:secciones,nombre',
            'descripcion' => 'nullable|string|max:255',
            'activa' => 'sometimes|boolean',
        ]);

        $seccion = Seccion::create($datos);

        return response()->json($seccion, 201);
    }

    // Consultar una sección por su ID
    public function show(Seccion $seccion)
    {
        return response()->json($seccion, 200);
    }

    // Modificar una sección
    public function update(Request $request, Seccion $seccion)
    {
        $datos = $request->validate([
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('secciones', 'nombre')->ignore($seccion->id),
            ],
            'descripcion' => 'sometimes|nullable|string|max:255',
            'activa' => 'sometimes|boolean',
        ]);

        $seccion->update($datos);

        return response()->json($seccion, 200);
    }

    // Eliminar una sección
    public function destroy(Seccion $seccion)
    {
        $seccion->delete();

        return response()->noContent();
    }
}
