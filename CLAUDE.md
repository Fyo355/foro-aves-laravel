@AGENTS.md

# Foro de Aves: práctica para la prueba técnica de Flesip (1 oct 2026)

Foro sobre aves: los usuarios publican posts (asociados a una especie) y comentan. Hay roles de usuario y administrador.
Objetivo: aprender haciendo. Sigue el estilo de aprendizaje de mi CLAUDE.md global (preguntas guía y el porqué de cada cosa).

## Stack
- Laravel 13, PHP 8.4 (Herd). Se sirve en http://flesip-practice.test
- Base de datos: **SQLite** (`database/database.sqlite`, `DB_CONNECTION=sqlite` en `.env`). No hay servidor MySQL.
- Frontend: React 18 (JSX, sin TypeScript) con Laravel Breeze + Inertia 2, Tailwind 3 con PostCSS y Vite 8.
- Tests: Pest, escritos a medida que se construye cada bloque.
- Breeze trae plantillas anteriores a Laravel 13. Al instalarlo hubo que subir `@vitejs/plugin-react` a ^6 (la v4 no admite Vite 8) y quitar `import './bootstrap'` de `app.jsx` (el skeleton ya no trae ese archivo). Si se reinstala Breeze, hay que repetir los dos arreglos.
- El modelo `User` usa atributos PHP de Laravel 13 (`#[Fillable]`, `#[Hidden]`) en lugar de `$fillable` y `$hidden`.

## Entorno Windows
- `php` y `composer` funcionan en PowerShell, no en Git Bash. Ejecuta `php artisan ...` desde PowerShell.

## Modelo de datos
- users: id, name, email, password, profile_photo (nullable), role (enum 'user'|'admin', default 'user')
- species: id, name
- posts: id, title, content, user_id (FK nullable, nullOnDelete), species_id (FK nullable, nullOnDelete)
- comments: id, content, user_id (FK nullable, nullOnDelete), post_id (FK obligatoria, cascadeOnDelete)
- Relaciones: User hasMany Post/Comment · Post belongsTo User/Species, hasMany Comment · Comment belongsTo User/Post · Species hasMany Post

## Decisiones cerradas (no reabrir sin motivo)
- `role` es ENUM: la BD rechaza valores inválidos.
- `species` es tabla relacional: el usuario elige de una lista y los filtros son fiables.
- Borrar un usuario no borra su contenido (estilo Reddit): posts y comentarios quedan con `user_id = NULL` y se muestran como "Usuario eliminado".
- En `comments` hay una asimetría a propósito: `user_id` usa nullOnDelete y `post_id` usa cascadeOnDelete.
- SQLite necesita las claves foráneas activas (`DB_FOREIGN_KEYS`, true por defecto). Si no, las reglas de borrado no se aplican.

## Roadmap
1. [x] Modelado de datos, migraciones de species/posts/comments y modelos con relaciones
2. [x] Migración de `role` y `profile_photo`, `User::isAdmin()`, estado `admin()` en UserFactory y seeder inicial
3. [x] Autenticación: Breeze con React (`composer require laravel/breeze --dev`, `php artisan breeze:install react`, `npm install`, `npm run dev`)
4. [ ] Roles y permisos (user/admin)
5. [ ] CRUD de posts con autorización (solo el autor edita y borra)
6. [ ] Comentarios (solo usuarios autenticados)
7. [ ] Panel de administrador: CRUD de usuarios y moderación
8. [ ] Perfil editable con foto (subida de archivos)
9. [ ] Tests con Pest para cada bloque

Actualiza este roadmap al terminar cada bloque.

## Plan hasta el jueves (acordado el 29 sep)
- Prioridad: un flujo completo con React (ruta → controlador → página Inertia/React que lista y crea posts). Es el mayor hueco para un puesto Fullstack.
- Roadmap recortado: autenticación, CRUD de posts con Policy y un par de tests. La foto de perfil y el panel de admin completo quedan para después.
- Hacer un ensayo cronometrado (~2 h) con un repo Laravel + React desconocido, explicando las decisiones en voz alta.
- Starter kits: Breeze 2.x admite Laravel 13 y sirve para aprender Inertia en este foro. Pero la documentación de Laravel 13 ya solo menciona los starter kits oficiales (`laravel new app --react`: Inertia + TypeScript + shadcn/ui), que solo se instalan al crear el proyecto. El ensayo del miércoles se hace con `laravel new ensayo --react` dentro de `~/Herd`.
- Herd: este proyecto está en el Escritorio, no en `~/Herd`, así que para servirlo en http://flesip-practice.test hace falta `herd link` desde su carpeta (con la app de Herd abierta).

## Puntos débiles a vigilar al revisar su código
- Copiar código sin revisarlo: imports que sobran o que faltan (un `#[Fillable]` sin su `use` falla sin avisar).
- Elegir una herramienta más pesada de lo necesario (una relación `belongsToMany` para lo que es una columna, una factory para un catálogo fijo). Pregúntale "¿qué es lo más simple que funciona?".
- Verificar antes de dar algo por terminado: ejecutarlo, `vendor/bin/pint` y comprobar el resultado.

## Datos de prueba
- `php artisan migrate:fresh --seed` crea `admin@example.com` (admin), `test@example.com` y `john@example.com` (usuarios normales) y 4 especies. Las contraseñas están en UserFactory.
- En PowerShell, `php artisan tinker --execute` pierde las comillas dobles. Para consultas, usa la herramienta `database-query` de Boost o un script con `php artisan tinker ruta\script.php`.
