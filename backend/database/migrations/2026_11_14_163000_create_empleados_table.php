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
       Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 150);

            $table->foreignId('seccion_id')
                ->constrained('secciones')
                ->restrictOnDelete();

            $table->enum('tipo_jornada', ['completa', 'parcial']);
            $table->enum('tipo_turno', ['fijo', 'rotativo']);
            $table->boolean('activo')->default(true);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
