<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HorarioController extends Controller
{
    // Consultar todos los horarios
    public function index()
    {
        return response()->json(Horario::all(), 200);
    }

    // Crear un horario
    public function store(Request $request)
    {
        $datos = $this->validarHorario($request);

        $horario = Horario::create($datos);

        return response()->json($horario, 201);
    }

    // Consultar un horario por su ID
    public function show(Horario $horario)
    {
        return response()->json($horario, 200);
    }

    // Modificar un horario
    public function update(Request $request, Horario $horario)
    {
        $datos = $this->validarHorario($request, $horario);

        $horario->update($datos);

        return response()->json($horario, 200);
    }

    // Eliminar un horario
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return response()->noContent();
    }

    // Validar los datos y comprobar el orden de las horas
    private function validarHorario(Request $request, ?Horario $horario = null): array
    {
        $reglas = [
            'nombre' => ($horario ? 'sometimes|required' : 'required') . '|string|max:100',
            'hora_inicio' => ($horario ? 'sometimes|required' : 'required') . '|date_format:H:i',
            'hora_fin' => ($horario ? 'sometimes|required' : 'required') . '|date_format:H:i',
            'activo' => 'sometimes|boolean',
        ];

        $validador = Validator::make($request->all(), $reglas);
        $datos = $validador->validate();

        $inicio = $datos['hora_inicio'] ?? $horario?->hora_inicio;
        $fin = $datos['hora_fin'] ?? $horario?->hora_fin;

        if ($inicio !== null && $fin !== null && $fin <= $inicio) {
            throw ValidationException::withMessages([
                'hora_fin' => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);
        }

        return $datos;
    }
}
