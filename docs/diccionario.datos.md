
# Diccionario de datos de GestionaTurnos

## 1. Descripción general

Este documento recoge los campos de las ocho entidades de la base de datos de GestionaTurnos, indicando su tipo SQL, nulabilidad, claves, valores por defecto, restricciones y descripción funcional.

Las claves primarias utilizan identificadores autoincrementales. Las claves foráneas mantienen las relaciones entre las entidades.

## 2. Diccionario de datos

### Usuario

Almacena los datos de acceso a la aplicación y el rol de cada usuario.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador del usuario |
| nombre | VARCHAR(100) | NO | — | — | NOT NULL | Nombre del usuario |
| email | VARCHAR(255) | NO | UNIQUE | — | NOT NULL, UNIQUE | Correo utilizado para iniciar sesión |
| password | VARCHAR(255) | NO | — | — | NOT NULL, contraseña con hash seguro | Contraseña de acceso |
| rol | ENUM('admin','empleado') | NO | — | — | Solo admite admin o empleado | Permisos del usuario |
| empleado_id | BIGINT UNSIGNED | SÍ | FK, UNIQUE | NULL | Referencia Empleado(id); único cuando tiene valor | Empleado asociado a la cuenta |

**Regla de negocio:** los usuarios con rol **empleado** tienen una ficha de empleado asociada. Los administradores pueden tenerla o no. Esta comprobación corresponde a Laravel.

### Empleado

Almacena los datos laborales de los trabajadores y su sección habitual.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador del empleado |
| nombre | VARCHAR(100) | NO | — | — | NOT NULL | Nombre del empleado |
| apellidos | VARCHAR(150) | NO | — | — | NOT NULL | Apellidos del empleado |
| seccion_id | BIGINT UNSIGNED | NO | FK | — | Referencia Sección(id) | Sección habitual del empleado |
| tipo_jornada | ENUM('completa','parcial') | NO | — | — | Solo admite completa o parcial | Tipo de jornada laboral |
| tipo_turno | ENUM('fijo','rotativo') | NO | — | — | Solo admite fijo o rotativo | Modalidad del turno |
| activo | BOOLEAN | NO | — | TRUE | NOT NULL | Indica si el empleado está activo |

### Sección

Almacena las diferentes secciones del supermercado.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador de la sección |
| nombre | VARCHAR(100) | NO | UNIQUE | — | NOT NULL, UNIQUE | Nombre de la sección |
| descripcion | VARCHAR(255) | SÍ | — | NULL | Campo opcional | Información adicional sobre la sección |
| activa | BOOLEAN | NO | — | TRUE | NOT NULL | Indica si la sección está activa |

### Horario

Almacena los horarios de los turnos de trabajo.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador del horario |
| nombre | VARCHAR(100) | NO | — | — | NOT NULL | Nombre del turno |
| hora_inicio | TIME | NO | — | — | NOT NULL | Hora de inicio del turno |
| hora_fin | TIME | NO | — | — | Posterior a hora_inicio | Hora de finalización del turno |
| activo | BOOLEAN | NO | — | TRUE | NOT NULL | Indica si el horario está disponible |

### Configuración

Almacena el número mínimo y máximo de empleados necesarios en cada sección y horario.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador de la configuración |
| seccion_id | BIGINT UNSIGNED | NO | FK | — | Referencia Sección(id) | Sección a la que corresponde |
| horario_id | BIGINT UNSIGNED | NO | FK | — | Referencia Horario(id) | Horario al que corresponde |
| minimo_empleados | INT | NO | — | — | CHECK (minimo_empleados >= 0) | Mínimo de empleados necesarios |
| maximo_empleados | INT | NO | — | — | CHECK (maximo_empleados >= minimo_empleados) | Máximo de empleados permitidos |

**Restricción conjunta:** **UNIQUE(seccion_id, horario_id)**. No existen dos configuraciones para la misma sección y horario.

### Ausencia

Almacena las vacaciones, bajas y otras ausencias de los empleados.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador de la ausencia |
| empleado_id | BIGINT UNSIGNED | NO | FK | — | Referencia Empleado(id) | Empleado al que corresponde |
| tipo | ENUM('vacaciones','baja','otro') | NO | — | — | Solo admite los tres valores indicados | Motivo de la ausencia |
| fecha_inicio | DATE | NO | — | — | NOT NULL | Primer día de ausencia |
| fecha_fin | DATE | NO | — | — | Igual o posterior a fecha_inicio | Último día de ausencia |
| observaciones | VARCHAR(255) | SÍ | — | NULL | Campo opcional | Información adicional |

### Cuadrante

Almacena la planificación semanal de los turnos de trabajo.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador del cuadrante |
| fecha_inicio | DATE | NO | UNIQUE | — | Lunes de la semana; UNIQUE | Primer día de la semana |
| fecha_fin | DATE | NO | — | — | Domingo de la misma semana | Último día de la semana |
| estado | ENUM('borrador','publicado') | NO | — | 'borrador' | Solo admite borrador o publicado | Estado del cuadrante |
| usuario_id | BIGINT UNSIGNED | NO | FK | — | Referencia Usuario(id) | Usuario que crea el cuadrante |

**Regla de negocio:** el usuario que crea un cuadrante tiene rol **admin**. Laravel comprueba que las fechas corresponden al lunes y domingo de la misma semana.

### Asignación

Registra los turnos de trabajo asignados a los empleados dentro de un cuadrante semanal.

| Campo | Tipo SQL | Nulo | Clave | Defecto | Restricciones / Reglas | Descripción funcional |
|---|---|---|---|---|---|---|
| id | BIGINT UNSIGNED | NO | PK | AUTO_INCREMENT | Identificador único | Identificador de la asignación |
| cuadrante_id | BIGINT UNSIGNED | NO | FK | — | Referencia Cuadrante(id) | Cuadrante al que pertenece |
| empleado_id | BIGINT UNSIGNED | NO | FK | — | Referencia Empleado(id) | Empleado asignado |
| horario_id | BIGINT UNSIGNED | NO | FK | — | Referencia Horario(id) | Horario de trabajo |
| seccion_id | BIGINT UNSIGNED | NO | FK | — | Referencia Sección(id) | Sección de trabajo |
| fecha | DATE | NO | — | — | Dentro de la semana del cuadrante | Día de trabajo |
| horas | DECIMAL(4,2) | NO | — | — | CHECK (horas > 0) | Horas previstas de trabajo |
| estado | ENUM('prevista','confirmada','cancelada') | NO | — | 'prevista' | Solo admite los tres estados indicados | Estado de la asignación |

**Restricción conjunta:** **UNIQUE(cuadrante_id, empleado_id, fecha, horario_id)**

**Reglas de negocio:** Laravel comprueba que las asignaciones activas de un empleado no se solapan en una misma fecha y que no coinciden con ausencias registradas. Las asignaciones canceladas no cuentan como activas.

## 3. Integridad referencial

Las claves foráneas relacionan las tablas y evitan referencias a registros inexistentes.

Las migraciones de Laravel definen las acciones **ON DELETE** de cada relación. Las entidades con información histórica, como las ausencias, los cuadrantes y las asignaciones, requieren especial atención para evitar eliminaciones accidentales.

## 4. Normalización

La justificación de la primera, segunda y tercera forma normal (1FN, 2FN y 3FN) se encuentra en **docs/modelo-datos.md**.
