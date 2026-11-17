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
       Schema::create('cuadrantes', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_inicio')->unique();

            $table->enum('estado', ['borrador', 'publicado'])
                ->default('borrador');

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuadrantes');
    }
};
