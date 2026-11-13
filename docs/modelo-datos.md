
# Modelo de datos de GestionaTurnos

## 1. Descripción general

La base de datos de GestionaTurnos permite almacenar la información necesaria para gestionar los empleados de un supermercado, las secciones, los horarios, las ausencias y los cuadrantes semanales.

El modelo incluye ocho entidades principales y contempla las relaciones necesarias para organizar las asignaciones de los empleados a los distintos turnos y secciones.

## 2. Entidades principales

### Usuario
Almacena los datos de acceso a la aplicación y el rol de cada usuario. Se mantiene separado de los datos laborales del empleado.

#### Campos de Usuario

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único del usuario. Clave primaria (PK). |
| nombre | VARCHAR(100) | Nombre del usuario. |
| email | VARCHAR(255) | Correo electrónico utilizado para iniciar sesión. Debe ser único. |
| password | VARCHAR(255) | Contraseña almacenada de forma segura mediante hash. |
| rol | ENUM('admin', 'empleado') | Determina los permisos del usuario. |
| empleado_id | BIGINT | Referencia al empleado asociado. Clave foránea (FK), opcional para administradores sin ficha laboral. |


### Empleado
Almacena los datos laborales de los trabajadores, su disponibilidad y su sección habitual.

#### Campos de Empleado

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único del empleado. Clave primaria (PK). |
| nombre | VARCHAR(100) | Nombre del empleado. |
| apellidos | VARCHAR(150) | Apellidos del empleado. |
| seccion_id | BIGINT | Sección habitual del empleado. Clave foránea (FK) que referencia a Sección. |
| tipo_jornada | ENUM('completa', 'parcial') | Indica si el empleado tiene jornada completa o parcial. |
| tipo_turno | ENUM('fijo', 'rotativo') | Indica si el empleado tiene un turno fijo o rotativo. |
| activo | BOOLEAN | Indica si el empleado está activo en la plantilla. |

### Sección
Almacena las diferentes secciones del supermercado.

#### Campos de Sección

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único de la sección. Clave primaria (PK). |
| nombre | VARCHAR(100) | Nombre de la sección. Debe ser único. |
| descripcion | VARCHAR(255) | Información adicional sobre la sección. Campo opcional. |
| activa | BOOLEAN | Indica si la sección está activa. |

### Horario
Almacena los turnos de mañana y tarde.


#### Campos de Horario

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único del horario. Clave primaria (PK). |
| nombre | VARCHAR(100) | Nombre del turno, por ejemplo, mañana o tarde. |
| hora_inicio | TIME | Hora a la que comienza el turno. |
| hora_fin | TIME | Hora a la que finaliza el turno. |
| activo | BOOLEAN | Indica si el horario está disponible para asignaciones. |

### Configuración
Almacena el número mínimo y máximo de empleados necesarios en cada sección y turno.

#### Campos de Configuración

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único de la configuración. Clave primaria (PK). |
| seccion_id | BIGINT | Sección a la que corresponde la configuración. Clave foránea (FK) que referencia a Sección. |
| horario_id | BIGINT | Horario al que corresponde la configuración. Clave foránea (FK) que referencia a Horario. |
| minimo_empleados | INT | Número mínimo de empleados necesarios en esa sección y horario. |
| maximo_empleados | INT | Número máximo de empleados que pueden estar asignados simultáneamente. |

### Ausencia
Almacena las vacaciones y bajas de los empleados.

#### Campos de Ausencia

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único de la ausencia. Clave primaria (PK). |
| empleado_id | BIGINT | Empleado al que corresponde la ausencia. Clave foránea (FK) que referencia a Empleado. |
| tipo | ENUM('vacaciones', 'baja', 'otro') | Motivo de la ausencia. |
| fecha_inicio | DATE | Primer día de la ausencia. |
| fecha_fin | DATE | Último día de la ausencia. |
| observaciones | VARCHAR(255) | Información adicional sobre la ausencia. Campo opcional. |


### Cuadrante
Almacena la planificación semanal de los turnos de trabajo.

#### Campos de Cuadrante

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único del cuadrante. Clave primaria (PK). |
| fecha_inicio | DATE | Primer día de la semana a la que corresponde el cuadrante. |
| fecha_fin | DATE | Último día de la semana a la que corresponde el cuadrante. |
| estado | ENUM('borrador', 'publicado') | Indica si el cuadrante está pendiente de revisión o disponible para los empleados. |
| usuario_id | BIGINT | Usuario administrador que crea el cuadrante. Clave foránea (FK) que referencia a Usuario. |


### Asignación
Registra qué empleado trabaja cada día, el turno y la sección a la que está asignado dentro de un cuadrante semanal. También almacena las horas de trabajo y el estado de la asignación.

#### Campos de Asignación

| Campo | Tipo de dato | Descripción |
|---|---|---|
| id | BIGINT | Identificador único de la asignación. Clave primaria (PK). |
| cuadrante_id | BIGINT | Cuadrante semanal al que pertenece. Clave foránea (FK) que referencia a Cuadrante. |
| empleado_id | BIGINT | Empleado al que corresponde la asignación. Clave foránea (FK) que referencia a Empleado. |
| horario_id | BIGINT | Horario asignado al empleado. Clave foránea (FK) que referencia a Horario. |
| seccion_id | BIGINT | Sección en la que trabaja el empleado ese día. Clave foránea (FK) que referencia a Sección. |
| fecha | DATE | Día concreto de trabajo. |
| horas | DECIMAL(4,2) | Número de horas de trabajo correspondientes a la asignación. |
| estado | ENUM('prevista', 'confirmada', 'cancelada') | Estado de la asignación. |
