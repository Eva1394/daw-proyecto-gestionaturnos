<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    public $timestamps = false;

    protected $fillable = [
        'seccion_id',
        'horario_id',
        'minimo_empleados',
        'maximo_empleados',
    ];

    protected $casts = [
        'minimo_empleados' => 'integer',
        'maximo_empleados' => 'integer',
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'seccion_id');
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }
}

