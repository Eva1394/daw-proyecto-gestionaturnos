<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuadrante extends Model
{
    protected $table = 'cuadrantes';

    public $timestamps = false;

    protected $fillable = [
        'fecha_inicio',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
    ];

    // Usuario que ha creado el cuadrante
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Asignaciones de empleados del cuadrante
    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'cuadrante_id');
    }
}
