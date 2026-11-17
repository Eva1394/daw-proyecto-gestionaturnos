<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cuadrante_id')
                ->constrained('cuadrantes')
                ->restrictOnDelete();

            $table->foreignId('empleado_id')
                ->constrained('empleados')
                ->restrictOnDelete();

            $table->foreignId('horario_id')
                ->constrained('horarios')
                ->restrictOnDelete();

            $table->foreignId('seccion_id')
                ->constrained('secciones')
                ->restrictOnDelete();

            $table->date('fecha');
            $table->decimal('horas', 4, 2);

            $table->enum('estado', ['prevista', 'confirmada', 'cancelada'])
                ->default('prevista');

            $table->unique([
                'cuadrante_id',
                'empleado_id',
                'fecha',
                'horario_id'
            ], 'asignacion_unica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
