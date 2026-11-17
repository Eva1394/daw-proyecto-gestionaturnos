<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
