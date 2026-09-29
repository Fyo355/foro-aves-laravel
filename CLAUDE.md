@AGENTS.md

# Foro de Aves: práctica para la prueba técnica de Flesip (1 oct 2026)

Foro sobre aves: los usuarios publican posts (asociados a una especie) y comentan. Hay roles de usuario y administrador.
Objetivo: aprender haciendo. Sigue el estilo de aprendizaje de mi CLAUDE.md global (preguntas guía y el porqué de cada cosa).

## Stack
- Laravel 13, PHP 8.4 (Herd). Se sirve en http://flesip-practice.test
- Base de datos: **SQLite** (`database/database.sqlite`, `DB_CONNECTION=sqlite` en `.env`). No hay servidor MySQL.
- Frontend previsto: React con Laravel Breeze + Inertia.js (aún sin instalar).
- Tests: Pest, escritos a medida que se construye cada bloque (aún sin instalar; ahora mismo solo está PHPUnit).
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
2. [ ] **Migración `add_role_and_profile_photo_to_users_table`: existe pero está VACÍA** (la escribe Fabrizio)
3. [ ] Autenticación: Breeze con React (`composer require laravel/breeze --dev`, `php artisan breeze:install react`, `npm install`, `npm run dev`)
4. [ ] Roles y permisos (user/admin)
5. [ ] CRUD de posts con autorización (solo el autor edita y borra)
6. [ ] Comentarios (solo usuarios autenticados)
7. [ ] Panel de administrador: CRUD de usuarios y moderación
8. [ ] Perfil editable con foto (subida de archivos)
9. [ ] Tests con Pest para cada bloque

Actualiza este roadmap al terminar cada bloque.
