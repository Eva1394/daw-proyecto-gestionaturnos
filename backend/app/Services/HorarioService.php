<?php

namespace App\Services;

use App\Models\Horario;

class HorarioService
{
    public function listar()
    {
        return Horario::all();
    }

    public function crear(array $datos)
    {
        return Horario::create($datos);
    }

    public function actualizar(Horario $horario, array $datos)
    {
        $horario->update($datos);

        return $horario;
    }

    public function eliminar(Horario $horario)
    {
        $horario->delete();
    }
}
