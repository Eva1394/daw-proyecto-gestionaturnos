

<h2>Indice</h2>

1. Justificación y dominio funcional 
  - 1.1. Elevator pitch (100–150 palabras) 
  - 1.2. Público objetivo y contexto de uso 
  
2. Roles del sistema y casos de uso 
  - 2.1. Matriz de roles (RBAC) 
  - 2.2. Casos de uso principales
  - 2.3. Alcance del MVP (Hito 3, diciembre) 
  
3. Entidades de negocio tentativas
  
4. Stack tecnológico previsto 

5. Selección tentativa de Add-ons





<h1>Hito 1 Anteproyecto: GestionaTurnos</h1>

---

**Centro:** CIFP Avilés (Curso 2026/2027)  

**Módulo:** Proyecto Intermodular (0379 · PIDW2) — 2º DAW Virtual 

**Autor/a:** Eva Rodríguez Pérez 

**Tutor/a:** Pablo Lera Menéndez

**Repositorio GitHub:** [Entregar en el Hito 1]

**Tablero GitHub Projects:**[Entregar en el Hito 1]

 


## 1. Justificación y dominio funcional ###  
---
### 1.1. Elevator pitch (100–150 palabras) 

GestionaTurnos es una aplicación web para facilitar la organización de los turnos de trabajo en los supermercados. 

Actualmente, elaborar los cuadrantes quita mucho tiempo a las encargadas de los diferentes supermercados, ya que deben tener en cuenta los turnos rotativos de los trabajadores, además de las vacaciones, bajas y demás necesidades del personal. 

La aplicación permitirá gestionar a los empleados, indicando en qué sección trabajan habitualmente y los puestos que pueden cubrir en caso necesario, organizar los diferentes turnos semanales y que los empleados consulten sus cuadrantes de trabajo. Con esta información, generará automáticamente los cuadrantes semanales, distribuyendo a los empleados para intentar mantener todas las secciones cubiertas. 

Las encargadas podrán revisar el resultado y realizar las modificaciones necesarias cuando ellas así lo consideren. 

El objetivo principal de este proyecto es reducir el tiempo dedicado a organizar los turnos y las distribuciones de los empleados. 

### 1.2. Público objetivo y contexto de uso  

GestionaTurnos va dirigido en especial a todos los encargados/as de los diferentes supermercados, ya que es un sector en el que la autora del proyecto trabaja actualmente y conoce de primera mano las dificultades que supone organizar los turnos de los empleados. Sin embargo, también podría ser utilizado en cualquier empresa en la que sea necesario organizar los turnos de los trabajadores de una forma similar. 

 

La aplicación se utilizará para crear los cuadrantes semanales teniendo en cuenta los turnos de los empleados, las necesidades de las distintas secciones y las ausencias por diferentes motivos de dichos empleados. 

También servirá para que los empleados puedan consultar sus horarios de trabajo. 

De esta manera, las encargadas podrán dedicar menos tiempo a esta labor y solo tendrán que revisar las propuestas que genere dicha aplicación lo que disminuiría en gran cantidad el tiempo que esto conlleva. 

---
 ## 2. Roles del sistema y casos de uso ###  
 ---

### 2.1. Matriz de roles (RBAC) -  


| Caso de uso | Público | Usuario registrado | Administrador |
|---|:---:|:---:|:---:|
| Acceder a la pantalla de inicio de sesión | ✔ | ✔ | ✔ |
| Iniciar y cerrar sesión | — | ✔ | ✔ |
| Consultar sus propios horarios y cuadrantes | — | ✔ | ✔ |
| Gestionar empleados (CRUD) | — | — | ✔ |
| Asignar funciones a los empleados | — | — | ✔ |
| Gestionar las secciones del supermercado | — | — | ✔ |
| Asignar empleados a las secciones | — | — | ✔ |
| Gestionar turnos y horarios de trabajo | — | — | ✔ |
| Configurar rotaciones de mañana y tarde | — | — | ✔ |
| Gestionar jornadas parciales y horarios especiales | — | — | ✔ |
| Registrar vacaciones, bajas y ausencias | — | — | ✔ |
| Establecer el mínimo y máximo de trabajadores por turno | — | — | ✔ |
| Establecer el personal necesario por sección | — | — | ✔ |
| Generar cuadrantes automáticamente | — | — | ✔ |
| Consultar los cuadrantes de todos los empleados | — | — | ✔ |
| Revisar y modificar los cuadrantes generados | — | — | ✔ |
| Detectar turnos o secciones sin personal suficiente | — | — | ✔ |
| Exportar cuadrantes a PDF o Excel | — | — | ✔ |

**Administrador:** Este rol corresponde a las encargadas/os, que podrán gestionar los empleados, añadir las funciones que puede desempeñar cada empleado, las secciones, los turnos, las vacaciones y las bajas. También podrán generar los cuadrantes, revisarlos y modificarlos si así lo consideran. 

**Usuario registrado:** Podrá iniciar sesión y consultar sus horarios de trabajo desde cualquier lugar. 

**Público / invitado:** En este caso no se dispone de público/invitado, ya que esta aplicación solo está destinada a empleados y encargados de la empresa. Las personas que no hayan sido registradas solo podrán acceder a la pantalla de inicio de sesión. 

### 2.2. Casos de uso principales (mínimo 6) 

 1. **CU-01: Registro e inicio de sesión seguro con tokens JWT. **

Las encargadas crearán las cuentas de los empleados para que puedan acceder a la aplicación. Tanto las encargadas como los empleados podrán iniciar sesión de una forma segura. 

*Flujo principal:*
- La encargada accede a la aplicación con su cuenta de administrador.
- La encargada crea una cuenta para el empleado.
- El empleado accede a la pantalla de inicio de sesión.
- Introduce sus credenciales de acceso.
- El sistema comprueba que las credenciales son correctas.
- Si son correctas, permite acceder a la aplicación según el rol del usuario.

*Flujo alternativo: *
-Si las credenciales son incorrectas, el sistema muestra un mensaje de error y no permite el acceso.

 2. **CU-02:** Gestión de empleados 

Las encargadas podrán añadir, modificar y eliminar empleados. También podrán indicar las secciones habituales de cada empleado y las que pueden cubrir, así como el tipo de jornada (completa o parcial) o el turno que tienen (fijo o rotativo). 

*Flujo principal:*
- La encargada inicia sesión y accede al apartado de gestión de empleados.
- Selecciona la opción de añadir un nuevo empleado.
- Introduce los datos del empleado, indicando su sección habitual, las secciones que puede cubrir, el tipo de jornada y el turno correspondiente.
- El sistema comprueba los datos introducidos y guarda al empleado.
- La encargada también podrá seleccionar un empleado existente para modificar sus datos o eliminarlo.

*Flujo alternativo: *
- Si falta algún dato obligatorio o es incorrecto, el sistema muestra un mensaje de error para que pueda corregirse.
- Si la encargada decide eliminar un empleado, el sistema solicita una confirmación antes de hacerlo.

3. **CU-03:** Gestión de secciones 
Las encargadas indicarán el número de personas necesarias para cada sección tanto en turno de tarde como de mañana. 

*Flujo principal: *
- La encargada inicia sesión y accede a las secciones.
- Selecciona la sección a la que desea acceder.
- Indica el número de empleados necesarios para cada turno.
- El sistema comprueba los datos introducidos y guarda la configuración.

*Flujo alternativo: *
- Si se introduce un número de trabajadores incorrecto, como puede ser un número negativo, el sistema muestra un mensaje de error para que pueda corregirse.

 4. **CU-04:** Gestión de vacaciones y bajas 

Las encargadas indicarán las vacaciones y bajas de cada empleado teniendo en cuenta que en ese período no estarán disponibles para trabajar. 

*Flujo principal: *
- La encargada inicia sesión y accede al apartado de vacaciones y bajas.
- Selecciona al empleado.
- Indica el motivo de ausencia y las fechas de inicio y fin de la misma.
- El sistema comprueba los datos introducidos y guarda la ausencia.
- El empleado no estará disponible durante esas fechas para la generación automática de cuadrantes.

*Flujo alternativo: *
- Si las fechas son incorrectas, el sistema muestra un mensaje de error.
- Si la encargada necesita modificar una ausencia, podrá seleccionarla y modificarla.

5. **CU-05:** Generación automatica de cuadrantes 

La aplicación generará automáticamente los cuadrantes de la semana teniendo en cuenta los turnos de los empleados, las vacaciones, las bajas y las necesidades de cada sección. También tendrá en cuenta las otras secciones que puede cubrir cada empleado para mantener todas las secciones cubiertas. 

*Flujo principal: *
- La encargada inicia sesión y accede al apartado de generación de cuadrantes.
- Selecciona la semana para la que quiere generar el cuadrante.
- El sistema consulta los empleados disponibles, sus turnos, las ausencias registradas y las necesidades de cada sección.
- La aplicación distribuye a los empleados entre los turnos de mañana y tarde.
- El sistema muestra el cuadrante generado para que la encargada pueda revisarlo.

*Flujo alternativo: *
- Si no hay suficientes empleados disponibles para cubrir una sección, el sistema muestra un aviso indicando donde falta el personal.
- Si la encargada no está conforme con alguna asignación, podrá modificarla manualmente.

6. **CU-06:** Revisión y modificación de cuadrantes 

Las encargadas podrán revisar los cuadrantes generados por la aplicación para asegurarse de que todas las secciones estén totalmente cubiertas y modificarlos si lo consideran necesario. 

*Flujo principal: *
- La encargada inicia sesión y accede al apartado de cuadrantes.
- Selecciona el cuadrante que desea revisar.
- El sistema muestra los empleados asignados a cada sección y turno.
- La encargada comprueba que las secciones estén cubiertas y, si es necesario, modifica las asignaciones.
- El sistema guarda los cambios y actualiza el cuadrante.

*Flujo alternativo: *
- Si la encargada intenta asignar a un empleado que está de vacaciones o de baja, el sistema muestra un aviso.
- Si alguna sección queda sin el personal necesario después de realizar una modificación, el sistema muestra un aviso para poder corregirlo.

7. **CU-07:** Consulta de horarios de trabajo 

Los empleados podrán iniciar sesión y consultar sus horarios de trabajo desde cualquier lugar. 

*Flujo principal: *
- El empleado inicia sesión en la aplicación.
- Accede al apartado de consulta de horarios.
- El sistema muestra el cuadrante correspondiente al empleado, indicando los días y turnos asignados.
- El empleado podrá consultar sus horarios de las semanas disponibles en la aplicación.

*Flujo alternativo: *
- Si el cuadrante no esta disponible todavía, el sistema mostrará un mensaje.
- Si el empleado intenta consultar un cuadrante al que no tiene permiso de acceso, el sistema deniega la consulta.

 ### 2.3. Alcance del MVP (Hito 3, diciembre) 

El MVP estará centrado en conseguir que las encargadas puedan introducir los datos básicos de los empleados, gestionar las secciones y ausencias, establecer el número mínimo y máximo de trabajadores necesarios por turno y generar automáticamente un cuadrante semanal que puedan revisar y modificar. Los usuarios también podrán acceder para consultar sus horarios.

**Incluido:**
 - Permitir el inicio de sesión de las encargadas y los empleados.
 - Introducir y modificar los datos básicos de los empleados, indicando su sección habitual y disponibilidad.
 - Establecer el número mínimo y máximo de trabajadores necesarios por sección, tanto por la mañana como por la tarde.
 - Registrar las vacaciones y bajas de los empleados, indicando las fechas en las que no estarán disponibles.
 - Generar automáticamente un cuadrante semanal de lunes a sábado.
 - Consultar el cuadrante generado, modificar manualmente las asignaciones y avisar si no se pueden cubrir los turnos.
 - Permitir que cada empleado consulte sus propios horarios de trabajo.

 

**Fuera del MVP:**
- Asignación automática de empleados a distintas secciones: tener en cuenta qué secciones puede cubrir cada trabajador.
- Rotación automática de turnos: alternar los turnos de mañana y tarde en función de las necesidades de cada empleado.
- Horarios especiales: gestionar jornadas parciales y horarios diferentes a los habituales.
- Gestión de descansos: organizar las horas de descanso.
- Exportación de cuadrantes: descargar los cuadrantes en PDF o Excel.

---
## 3. Entidades de negocio tentativas

--- 
**Entidades principales**
*Empleado:* almacena los datos de los trabajadores, su disponibilidad y su sección habitual.

*Sección: * almacena las distintas secciones del supermercado.

*Horario:* almacena los turnos de mañana y tarde.

*Configuracion:* almacena el número mínimo y máximo de trabajadores necesarios en cada sección y turno.

*Ausencia: * almacena las vacaciones y bajas de los empleados.

*Cuadrante: * almacena la planificación semanal de los turnos de trabajo

*Asignacion:* almacena qué empleado trabaja cada día y en qué sección y en qué horario.  

*Usuario: * almacena los datos de acceso a la aplicacion y el rol correspondiente. Estará separado de los datos laborales del empleado.

**Relación transaccional principal prevista **
*Empleado-Horario:* Un empleado puede trabajar en distintos horarios y un mismo horario puede ser asignado a varios empleados. La entidad intermedia Asignación permite registrar qué empleado trabaja cada día, en qué turno y en qué sección, dentro de un cuadrante semanal.
 

 ---  

## 4. Stack tecnológico previsto 
---

 - **Motor de base de datos:** 

<u>MySQL: </u>
Se ha elegido este gestor porque permite guardar y relacionar los datos de los empleados, las secciones, los horarios y los cuadrantes de trabajo. Además, ya se ha utilizado durante el curso anterior, por lo que se dispone de conocimientos previos.

 - **Backend API:** 

<u>Laravel: </u> Es un framework de PHP que permitirá desarrollar la parte interna de la aplicación, como la gestión de los empleados y la generación de los cuadrantes. Además, se estudiará durante el curso, por lo que se podrán aplicar los conocimientos adquiridos al proyecto.

- **Frontend:** 

<u>React:</u> Permite crear interfaces web interactivas y organizar la aplicación en diferentes componentes. Con React se desarrollarán las pantallas donde las encargadas gestionarán los turnos y los empleados podrán consultar sus horarios. 

- **Alojamiento cloud previsto:** 

<u>AWS: </u>Ofrece servicios de alojamiento en la nube que permitirán publicar GestionaTurnos para así poder acceder a la aplicación desde internet. Además, se estudiará durante el curso, por lo que se podrán aplicar los conocimientos adquiridos.

---
## 5. Selección tentativa de Add-ons (mínimo 2)  
---

- Generación de informes en PDF/Excel en backend (+0.5)  
Con esta función se podrán descargar los cuadrantes de trabajo en PDF o Excel para que se puedan guardar o imprimir.

- Batería de pruebas automatizadas (>40 % de endpoints) (+1.0) 
Se realizarán pruebas automatizadas para comprobar que el backend funciona correctamente, lo que ayudará a detectar errores en la aplicación.

- Documentación OpenAPI / Swagger interactiva (+0.5) 
Permitirá documentar las diferentes funciones de la API, además de probar las peticiones de forma sencilla y comprobar sus respuestas.