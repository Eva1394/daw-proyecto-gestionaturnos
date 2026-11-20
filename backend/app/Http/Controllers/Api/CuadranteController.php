<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuadrante;
use App\Services\CuadranteService;
use Illuminate\Http\Request;

class CuadranteController extends Controller
{
    public function __construct(
        private CuadranteService $cuadranteService
    ) {
    }

    // Consultar todos los cuadrantes
    public function index()
    {
        return response()->json(
            $this->cuadranteService->listar(),
            200
        );
    }

    // Crear un cuadrante semanal
    public function store(Request $request)
    {
        $datos = $this->validarCuadrante($request);

        $cuadrante = $this->cuadranteService->crear($datos);

        return response()->json($cuadrante, 201);
    }

    // Consultar un cuadrante por su ID
    public function show(Cuadrante $cuadrante)
    {
        return response()->json(
            $this->cuadranteService->consultar($cuadrante),
            200
        );
    }

    // Modificar un cuadrante
    public function update(Request $request, Cuadrante $cuadrante)
    {
        $datos = $this->validarCuadrante($request, true);

        $cuadrante = $this->cuadranteService->actualizar(
            $cuadrante,
            $datos
        );

        return response()->json($cuadrante, 200);
    }

    // Eliminar un cuadrante
    public function destroy(Cuadrante $cuadrante)
    {
        $this->cuadranteService->eliminar($cuadrante);

        return response()->noContent();
    }

    // Validar los datos recibidos
    private function validarCuadrante(
        Request $request,
        bool $actualizando = false
    ): array {
        return $request->validate([
            'fecha_inicio' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'date_format:Y-m-d',
            ],
            'estado' => [
                'sometimes',
                'required',
                'in:borrador,publicado',
            ],
            'usuario_id' => [
                $actualizando ? 'sometimes' : 'required',
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);
    }
}