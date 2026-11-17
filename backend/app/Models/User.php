<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Campos que se pueden guardar en la base de datos
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'empleado_id',
    ];

    // Campos que no deben mostrarse en las respuestas
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Conversión automática de datos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación entre el usuario y su empleado
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}

