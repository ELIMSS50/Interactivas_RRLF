# Torneos — Examen 2

Aplicación en Laravel para administrar torneos e inscribir jugadores (sin carrito, sin pagos y sin brackets).

- **Administrador:** crea, edita y elimina torneos, ve los inscritos y da de baja a cualquier inscripción.
- **Jugador:** se inscribe en torneos disponibles, ve sus torneos y cancela su inscripción.
- **Visitante (sin cuenta):** solo consulta los torneos disponibles y su detalle.

## Tecnologías

- Laravel 13 (PHP 8.4) con vistas Blade renderizadas en el servidor
- SQLite (`database/database.sqlite`)
- Tailwind CSS 4 compilado con Vite, fuente Instrument Sans e íconos SVG (componente `<x-icono>`)

## Instalación

Requisitos: PHP 8.3+ con las extensiones `pdo_sqlite` y `sqlite3`, Composer, Node.js y npm.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

La aplicación queda en http://127.0.0.1:8000

Para reiniciar la base de datos desde cero con los datos de demostración:

```bash
php artisan migrate:fresh --seed
```

## Cuentas demo

Se crean con los seeders al ejecutar `php artisan migrate --seed` (o `php artisan db:seed`).

| Rol | Correo | Contraseña | Seeder |
|-----|--------|------------|--------|
| Administrador | `admin@torneos.com` | `admin12345` | `AdminSeeder` |
| Jugador | `ana@torneos.com` | `jugador123` | `DemoSeeder` |
| Jugador | `carlos@torneos.com` | `jugador123` | `DemoSeeder` |
| Jugador | `maria@torneos.com` | `jugador123` | `DemoSeeder` |
| Jugador | `pedro@torneos.com` | `jugador123` | `DemoSeeder` (sin inscripciones) |

La cuenta de administrador **solo** se crea con el seeder; el registro público siempre crea jugadores. Para crear o restablecer únicamente el administrador:

```bash
php artisan db:seed --class=AdminSeeder
```

### Torneos demo

Las fechas se calculan a partir del día en que se ejecuta el seeder, así que cada uno sirve para probar un caso:

| Torneo | Fecha | Cupo | Estado | Inscritos | Sirve para probar |
|--------|-------|------|--------|-----------|-------------------|
| Copa FIFA Otoño | hoy + 7 | 16 | abierto | Ana, Carlos | Torneo disponible con inscritos |
| Torneo de Ajedrez Relámpago | hoy + 14 | 8 | abierto | María | Torneo disponible |
| Gran Final Smash | hoy + 30 | 32 | abierto | — | Torneo sin participantes |
| Liga de Pádel Express | hoy + 10 | 2 | abierto | Carlos, María | Torneo **lleno** |
| Copa de Tenis Cerrada | hoy + 20 | 16 | **cerrado** | Ana | Torneo **cerrado** |
| Fútbol Rápido de Hoy | hoy | 10 | abierto | Ana | Fecha alcanzada (no inscribe ni cancela) |


```bash
php artisan db:seed --class=DemoSeeder
```

## Roles y acceso

| Rol | Cómo se obtiene | Qué puede hacer |
|-----|-----------------|-----------------|
| Visitante | Sin cuenta | Consultar torneos disponibles y su detalle |
| Jugador (`jugador`) | Registrándose en `/registro` | Inscribirse, ver sus torneos y cancelar |
| Administrador (`admin`) | Seeder `AdminSeeder` | Gestionar torneos e inscripciones |


## Torneos

| Campo | Reglas |
|-------|--------|
| `nombre` | Obligatorio, máx. 255 caracteres |
| `juego` | Juego o deporte, obligatorio, máx. 100 caracteres |
| `fecha` | Obligatoria, debe ser futura (posterior a hoy) |
| `cupo` | Entre 2 y 100, por defecto 16. Al editar no puede ser menor al número de inscritos |
| `descripcion` | Opcional, máx. 2000 caracteres |
| `estado` | `abierto` o `cerrado` (por defecto `abierto`) |



### Disponibilidad

Un torneo está **disponible** cuando está `abierto`, su fecha es posterior a hoy y tiene plazas libres la página principal muestra los torneos disponibles y, en gris y sin opción de inscribirse, los torneos **cerrados** con fecha futura; todos ordenados por la fecha más próxima. Los torneos llenos o cuya fecha ya llegó no aparecen.

### Inscripción y cancelación

- El botón *Inscribirme* solo aparece en torneos disponibles, y el servidor vuelve a validar: bloquea duplicados (además hay un índice único `torneo_id` + `user_id`), torneos cerrados, llenos o cuya fecha ya llegó, mostrando un aviso.
- El jugador puede cancelar su inscripción hasta el día anterior al torneo. Al cancelar se libera la plaza.
- El administrador puede dar de baja cualquier inscripción en cualquier momento.



### Mensajes

- Los errores de validación aparecen **debajo de cada campo**, que además se marca en rojo, y arriba del formulario se muestra *"No se pudo guardar: revisa los campos marcados en rojo."*
- Las acciones correctas muestran un aviso verde (por ejemplo *"Torneo "X" creado correctamente."*) y los bloqueos un aviso rojo.

## Cómo probar cada punto

Iniciar el servidor con `php artisan serve` y abrir http://127.0.0.1:8000. Las cuentas son las de la sección [Cuentas demo](#cuentas-demo).

Antes de empezar conviene reiniciar los datos con `php artisan migrate:fresh --seed`.

### 1. Consulta pública (sin cuenta)

1. Abrir la página principal sin iniciar sesión.
2. Se ven los torneos disponibles ordenados por fecha.
3. Entrar al detalle de un torneo: se ve la información, pero en lugar de *Inscribirme* pide iniciar sesión.
4. Intentar abrir `/admin` o `/mis-torneos` sin sesión: redirige al login.

### 2. Registro e inicio de sesión

1. Ir a **Registrarse** (`/registro`) y crear una cuenta nueva. Entra como jugador.
2. Probar a enviar el formulario vacío o con un correo ya usado: aparecen los errores debajo de cada campo.
3. Cerrar sesión y entrar con `ana@torneos.com` / `jugador123` desde `/login`.
4. Probar una contraseña incorrecta: sale "El correo o la contraseña son incorrectos." y no deja entrar.

### 3. Administrador: gestión de torneos

Entrar con `admin@torneos.com` / `admin12345`.

1. **Crear:** ir a *Torneos → Nuevo torneo* y guardar uno con datos válidos. Aparece el aviso verde y el torneo en la lista.
2. **Validaciones:** intentar guardar con campos vacíos, una fecha de hoy o pasada, o un cupo de 1 o 200. Cada error sale debajo de su campo.
3. **Editar:** cambiar el nombre o el estado de un torneo y guardar.
4. **Cupo menor a inscritos:** editar *Copa FIFA Otoño* (2 inscritos) y poner cupo 1. No lo permite.
5. **Eliminar:** borrar *Gran Final Smash*. Desaparece de la lista y de la página principal.

### 4. Administrador: inscripciones

1. En la lista de torneos, abrir los inscritos de *Copa FIFA Otoño*: aparecen Ana y Carlos.
2. Dar de baja a uno. Se libera la plaza.
3. el administrador sí puede dar de baja aunque la fecha ya haya llegado.
4. Con una cuenta de jugador, intentar abrir `/admin`: no tiene acceso.

### 5. Jugador: inscribirse y cancelar

Entrar con `pedro@torneos.com` / `jugador123` (no tiene inscripciones).

1. **Inscribirse:** abrir *Torneo de Ajedrez Relámpago* y pulsar *Inscribirme*. Sale el aviso verde.
2. **Mis torneos:** ir a `/mis-torneos` y ver el torneo en la lista.
3. **Duplicado:** volver al mismo torneo; ya no aparece el botón de inscribirse.
4. **Cancelar:** cancelar la inscripción desde *Mis torneos*. La plaza vuelve a quedar libre.
5. **Torneo cerrado o lleno:** *Copa de Tenis Cerrada* no deja inscribirse y *Liga de Pádel Express* no aparece por estar llena.
6. **Fecha alcanzada:** entrar con `ana@torneos.com`; en *Mis torneos*, *Fútbol Rápido de Hoy* ya no se puede cancelar.

### 6. Datos de prueba

1. Ejecutar `php artisan migrate:fresh --seed`.
2. Comprobar que se pueden usar todas las cuentas de [Cuentas demo](#cuentas-demo) y que aparecen los torneos de la tabla [Torneos demo](#torneos-demo).


