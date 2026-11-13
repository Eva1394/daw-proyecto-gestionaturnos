
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


## 3. Relaciones entre entidades

### Usuario - Empleado (1:1)
Un usuario puede estar asociado a un empleado, pero esta relación es opcional para los administradores que no tengan ficha laboral. Cada empleado puede tener como máximo una cuenta de usuario.

### Sección - Empleado (1:N)
Una sección puede tener varios empleados asociados. Cada empleado tiene una sección habitual.

### Sección - Configuración (1:N)
Una sección puede tener distintas configuraciones según el horario.

### Horario - Configuración (1:N)
Un horario puede tener distintas configuraciones según la sección.

### Empleado - Ausencia (1:N)
Un empleado puede registrar varias ausencias. Cada ausencia pertenece a un único empleado.

### Usuario - Cuadrante (1:N)
Un usuario administrador puede crear varios cuadrantes. Cada cuadrante tiene un usuario creador.

### Cuadrante - Asignación (1:N)
Un cuadrante contiene varias asignaciones. Cada asignación pertenece a un único cuadrante.

### Empleado - Asignación (1:N)
Un empleado puede tener varias asignaciones de trabajo. Cada asignación corresponde a un único empleado.

### Horario - Asignación (1:N)
Un horario puede aparecer en varias asignaciones. Cada asignación utiliza un único horario.

### Sección - Asignación (1:N)
Una sección puede aparecer en varias asignaciones. Cada asignación corresponde a una única sección.

### Empleado - Horario (M:N)
Un empleado puede trabajar en distintos horarios y un mismo horario puede corresponder a varios empleados. Esta relación se resuelve mediante la entidad Asignación, que almacena además la fecha, la sección, las horas de trabajo y el estado.


## 4. Restricciones de integridad

Para garantizar la coherencia de los datos, se establecen las siguientes restricciones:

- **Claves primarias (PK):** todas las entidades tienen un campo `id` único y obligatorio.
- **Claves foráneas (FK):** permiten relacionar las entidades y evitan referencias a registros inexistentes.
- **Usuario:** el correo electrónico debe ser único. El campo `empleado_id` puede ser nulo, pero, cuando tenga un valor, este debe ser único.
- **Sección:** el nombre de cada sección debe ser único.
- **Configuración:** no puede existir más de una configuración para la misma combinación de sección y horario. El mínimo de empleados no puede ser negativo y el máximo debe ser igual o superior al mínimo.
- **Ausencia:** la fecha de finalización debe ser igual o posterior a la fecha de inicio.
- **Cuadrante:** la fecha de finalización debe ser igual o posterior a la fecha de inicio. No pueden existir dos cuadrantes para la misma semana.
- **Asignación:** las horas de trabajo deben ser mayores que cero. No se pueden duplicar asignaciones para el mismo empleado, fecha y horario dentro de un cuadrante.


## 5. Normalización de la base de datos

El modelo de datos de GestionaTurnos se ha diseñado siguiendo las tres primeras formas normales para evitar duplicidades y mantener la coherencia de la información.

### Primera forma normal (1FN)

Todas las tablas tienen una clave primaria que identifica cada registro de forma única.

Los campos almacenan valores individuales y no existen grupos de datos repetidos dentro de una misma columna.

Por ejemplo, las ausencias de un empleado se almacenan en registros independientes de la tabla Ausencia, en lugar de guardar varias fechas en un único campo de Empleado.

### Segunda forma normal (2FN)

El modelo cumple la primera forma normal y los atributos de cada tabla dependen de su clave primaria completa.

Todas las entidades utilizan una clave primaria simple (`id`), por lo que no existen dependencias parciales de una clave primaria compuesta.

Por ejemplo, en Asignación, los campos fecha, horas y estado corresponden a una asignación concreta.

### Tercera forma normal (3FN)

El modelo cumple la segunda forma normal y evita almacenar atributos que dependan de otros atributos no clave.

La información se distribuye entre entidades relacionadas mediante claves foráneas.

Por ejemplo, Asignación almacena `seccion_id` en lugar de repetir el nombre de la sección. Del mismo modo, Empleado almacena `seccion_id` para identificar su sección habitual.

Esto permite modificar el nombre de una sección una sola vez, sin tener que actualizar todos los empleados o las asignaciones relacionados.

La entidad Configuración almacena las necesidades de personal de cada combinación de sección y horario, evitando repetir estos datos en cada asignación.

