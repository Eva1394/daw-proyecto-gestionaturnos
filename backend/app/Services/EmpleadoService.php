<?php

namespace App\Services;

use App\Models\Empleado;

class EmpleadoService
{
    public function listar()
    {
        return Empleado::with('seccion')->get();
    }

    public function crear(array $datos)
    {
        return Empleado::create($datos)->load('seccion');
    }

    public function consultar(Empleado $empleado)
    {
        return $empleado->load('seccion');
    }

    public function actualizar(Empleado $empleado, array $datos)
    {
        $empleado->update($datos);

        return $empleado->load('seccion');
    }

    public function eliminar(Empleado $empleado)
    {
        $empleado->delete();
    }
}
