<?php

namespace App\Services;

use App\Models\Seccion;

class SeccionService
{
    public function listar()
    {
        return Seccion::all();
    }

    public function crear(array $datos)
    {
        return Seccion::create($datos);
    }

    public function actualizar(Seccion $seccion, array $datos)
    {
        $seccion->update($datos);

        return $seccion;
    }

    public function eliminar(Seccion $seccion)
    {
        $seccion->delete();
    }
}
