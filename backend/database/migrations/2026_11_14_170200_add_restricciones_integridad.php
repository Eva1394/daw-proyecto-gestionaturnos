
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE horarios
            ADD CONSTRAINT chk_horarios_horas
            CHECK (hora_fin > hora_inicio)
        ");

        DB::statement("
            ALTER TABLE configuraciones
            ADD CONSTRAINT chk_configuraciones_minimo
            CHECK (minimo_empleados >= 0)
        ");

        DB::statement("
            ALTER TABLE configuraciones
            ADD CONSTRAINT chk_configuraciones_maximo
            CHECK (maximo_empleados >= minimo_empleados)
        ");

        DB::statement("
            ALTER TABLE ausencias
            ADD CONSTRAINT chk_ausencias_fechas
            CHECK (fecha_fin >= fecha_inicio)
        ");

        DB::statement("
            ALTER TABLE asignaciones
            ADD CONSTRAINT chk_asignaciones_horas
            CHECK (horas > 0)
        ");
    }

    
    public function down(): void
    {
        DB::statement("ALTER TABLE asignaciones DROP CONSTRAINT chk_asignaciones_horas");
        DB::statement("ALTER TABLE ausencias DROP CONSTRAINT chk_ausencias_fechas");
        DB::statement("ALTER TABLE configuraciones DROP CONSTRAINT chk_configuraciones_maximo");
        DB::statement("ALTER TABLE configuraciones DROP CONSTRAINT chk_configuraciones_minimo");
        DB::statement("ALTER TABLE horarios DROP CONSTRAINT chk_horarios_horas");
    }

};
