<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    public $timestamps = false;

    protected $fillable = [
        'cuadrante_id',
        'empleado_id',
        'horario_id',
        'seccion_id',
        'fecha',
        'horas',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date:Y-m-d',
        'horas' => 'decimal:2',
    ];

    // Cuadrante al que pertenece la asignación
    public function cuadrante(): BelongsTo
    {
        return $this->belongsTo(Cuadrante::class, 'cuadrante_id');
    }

    // Empleado que realiza el turno
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    // Horario de trabajo asignado
    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    // Sección en la que trabaja el empleado
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id');
    }
}
