<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
    protected $table = 'empleados';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'seccion_id',
        'tipo_jornada',
        'tipo_turno',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id');
    }
}

