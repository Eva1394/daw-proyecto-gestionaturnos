<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GestionaTurnosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SECCIONES
        $secciones = [
            'Cajas',
            'Carnicería',
            'Charcutería',
            'Pescadería',
            'Frutería',
            'Panadería',
            'Reposición',
            'Almacén',
            'Congelados',
            'Atención al cliente',
        ];

        foreach ($secciones as $nombre) {
            DB::table('secciones')->updateOrInsert(
                ['nombre' => $nombre],
                [
                    'descripcion' => 'Sección de ' . $nombre,
                    'activa' => true,
                ]
            );
        }

        // 2. HORARIOS
        $horarios = [
            ['Mañana general', '07:30', '14:30'],
            ['Tarde general', '14:30', '21:30'],
            ['Mañana apertura', '06:30', '13:30'],
            ['Tarde cierre', '14:00', '21:00'],
            ['Mañana parcial', '06:30', '10:30'],
            ['Mañana parcial rotativa', '09:00', '14:30'],
            ['Tarde parcial rotativa', '16:00', '21:30'],
            ['Mañana intermedia', '08:00', '15:00'],
            ['Tarde intermedia', '13:00', '20:00'],
            ['Refuerzo de mañana', '10:00', '14:00'],
        ];

        foreach ($horarios as $horario) {
            DB::table('horarios')->updateOrInsert(
                ['nombre' => $horario[0]],
                [
                    'hora_inicio' => $horario[1],
                    'hora_fin' => $horario[2],
                    'activo' => true,
                ]
            );
        }

        // 3. EMPLEADOS
        $empleados = [
            ['Ana', 'García López', 'Cajas', 'completa', 'rotativo'],
            ['Luis', 'Fernández Pérez', 'Carnicería', 'completa', 'rotativo'],
            ['Marta', 'Rodríguez Díaz', 'Charcutería', 'completa', 'rotativo'],
            ['Pablo', 'Martínez Ruiz', 'Pescadería', 'completa', 'rotativo'],
            ['Laura', 'Sánchez Castro', 'Frutería', 'completa', 'fijo'],
            ['Carlos', 'López Gómez', 'Panadería', 'parcial', 'fijo'],
            ['Elena', 'Pérez Vázquez', 'Reposición', 'completa', 'rotativo'],
            ['David', 'Álvarez García', 'Almacén', 'completa', 'rotativo'],
            ['Sara', 'González Fernández', 'Congelados', 'parcial', 'rotativo'],
            ['Jorge', 'Díaz Martínez', 'Atención al cliente', 'completa', 'fijo'],
        ];

        foreach ($empleados as $empleado) {
            $seccionId = DB::table('secciones')
                ->where('nombre', $empleado[2])
                ->value('id');

            DB::table('empleados')->updateOrInsert(
                [
                    'nombre' => $empleado[0],
                    'apellidos' => $empleado[1],
                ],
                [
                    'seccion_id' => $seccionId,
                    'tipo_jornada' => $empleado[3],
                    'tipo_turno' => $empleado[4],
                    'activo' => true,
                ]
            );
        }

        // 4. USUARIOS
        $usuarios = [
            ['Administrador', 'admin@cifpaviles.es', 'admin', null],
            ['Ana García', 'user1@cifpaviles.es', 'empleado', 'Ana'],
            ['Luis Fernández', 'user2@cifpaviles.es', 'empleado', 'Luis'],
            ['Marta Rodríguez', 'user3@cifpaviles.es', 'empleado', 'Marta'],
            ['Pablo Martínez', 'user4@cifpaviles.es', 'empleado', 'Pablo'],
            ['Laura Sánchez', 'user5@cifpaviles.es', 'empleado', 'Laura'],
            ['Carlos López', 'user6@cifpaviles.es', 'empleado', 'Carlos'],
            ['Elena Pérez', 'user7@cifpaviles.es', 'empleado', 'Elena'],
            ['David Álvarez', 'user8@cifpaviles.es', 'empleado', 'David'],
            ['Sara González', 'user9@cifpaviles.es', 'empleado', 'Sara'],
        ];

        foreach ($usuarios as $usuario) {
            $empleadoId = null;

            if ($usuario[3] !== null) {
                $empleadoId = DB::table('empleados')
                    ->where('nombre', $usuario[3])
                    ->value('id');
            }

            $usuarioExistente = DB::table('users')
                ->where('email', $usuario[1])
                ->first();

            $contrasena = match ($usuario[1]) {
                'admin@cifpaviles.es' => 'Admin1234!',
                'user1@cifpaviles.es',
                'user2@cifpaviles.es' => 'User1234!',
                default => 'Gestiona2026!',
            };

            DB::table('users')->updateOrInsert(
                ['email' => $usuario[1]],
                [
                    'nombre' => $usuario[0],
                    'password' => Hash::make($contrasena),
                    'rol' => $usuario[2],
                    'empleado_id' => $empleadoId,
                    'created_at' => $usuarioExistente
                        ? $usuarioExistente->created_at
                        : now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 5. CONFIGURACIONES
        foreach ($secciones as $i => $seccion) {
            $seccionId = DB::table('secciones')
                ->where('nombre', $seccion)
                ->value('id');

            $horarioId = DB::table('horarios')
                ->where('nombre', $horarios[$i][0])
                ->value('id');

            DB::table('configuraciones')->updateOrInsert(
                [
                    'seccion_id' => $seccionId,
                    'horario_id' => $horarioId,
                ],
                [
                    'minimo_empleados' => 1,
                    'maximo_empleados' => $seccion === 'Cajas' ? 4 : 3,
                ]
            );
        }

        // 6. AUSENCIAS
        foreach ($empleados as $i => $empleado) {
            $empleadoId = DB::table('empleados')
                ->where('nombre', $empleado[0])
                ->value('id');

            $inicio = \Carbon\Carbon::parse('2027-06-01')
                ->addDays($i * 5);

            DB::table('ausencias')->updateOrInsert(
                [
                    'empleado_id' => $empleadoId,
                    'fecha_inicio' => $inicio->toDateString(),
                    'fecha_fin' => $inicio->copy()
                        ->addDays(2)->toDateString(),
                ],
                [
                    'tipo' => $i % 2 === 0 ? 'vacaciones' : 'baja',
                    'observaciones' => 'Ausencia de prueba',
                ]
            );
        }

        // 7. CUADRANTES Y 8. ASIGNACIONES
        $adminId = DB::table('users')
            ->where('email', 'admin@cifpaviles.es')
            ->value('id');

        foreach (range(0, 9) as $i) {
            $lunes = \Carbon\Carbon::parse('2027-01-04')
                ->addWeeks($i)
                ->toDateString();

            DB::table('cuadrantes')->updateOrInsert(
                ['fecha_inicio' => $lunes],
                [
                    'estado' => 'borrador',
                    'usuario_id' => $adminId,
                ]
            );

            $cuadranteId = DB::table('cuadrantes')
                ->where('fecha_inicio', $lunes)
                ->value('id');

            $empleado = $empleados[$i];

            $empleadoId = DB::table('empleados')
                ->where('nombre', $empleado[0])
                ->value('id');

            $seccionId = DB::table('secciones')
                ->where('nombre', $empleado[2])
                ->value('id');

            $horarioId = DB::table('horarios')
                ->where('nombre', $horarios[$i][0])
                ->value('id');

            DB::table('asignaciones')->updateOrInsert(
                [
                    'cuadrante_id' => $cuadranteId,
                    'empleado_id' => $empleadoId,
                    'fecha' => $lunes,
                    'horario_id' => $horarioId,
                ],
                [
                    'seccion_id' => $seccionId,
                    'horas' => 4.00,
                    'estado' => 'prevista',
                ]
            );
        }
    }
}
