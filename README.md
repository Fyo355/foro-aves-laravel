# 🐦 Foro de Aves

Foro comunitario para publicar y comentar avistamientos y contenido sobre aves, construido con **Laravel** como proyecto de práctica para reforzar conceptos de backend, relaciones de base de datos, autenticación y testing.

## Funcionalidades

- 📝 Creación de posts categorizados por especie de ave.
- 💬 Sistema de comentarios (solo para usuarios registrados).
- 👤 Roles de **usuario** y **administrador**.
  - El autor de un post puede editarlo o eliminarlo.
  - El administrador cuenta con un panel exclusivo: CRUD de usuarios y moderación de posts/comentarios de cualquier usuario.
- 🖼️ Perfil de usuario editable con foto de perfil.
- 🗑️ Manejo de usuarios eliminados: sus posts y comentarios se conservan mostrando "Usuario eliminado" (patrón similar a Reddit), en vez de borrarse en cascada.
- ✅ Suite de tests (Pest/PHPUnit) que acompaña cada funcionalidad.

## Stack

- **Backend**: Laravel 12, PHP 8.4
- **Base de datos**: MySQL 8 (Docker)
- **Testing**: Pest
- **Entorno**: Docker Compose (MySQL + phpMyAdmin)

## Modelo de datos

```
users     → id, name, email, password, profile_photo, role (enum: user/admin)
species   → id, name
posts     → id, title, content, user_id (FK), species_id (FK)
comments  → id, content, user_id (FK), post_id (FK)
```

### Decisiones de diseño destacadas

- **`role` como ENUM** en lugar de valores numéricos: la base de datos garantiza que solo existan los roles `user` y `admin`, evitando datos inconsistentes.
- **`species` como tabla relacional** en lugar de texto libre: permite un buscador/filtro fiable por especie sin errores de escritura.
- **Borrado de usuario no destructivo**: los posts y comentarios de un usuario eliminado se conservan (`nullOnDelete`), mientras que los comentarios de un post eliminado sí se eliminan en cascada (`cascadeOnDelete`), ya que un comentario sin post no tiene sentido.

## Instalación local

```bash
git clone https://github.com/TU_USUARIO/foro-aves-laravel.git
cd foro-aves-laravel
composer install
cp .env.example .env
php artisan key:generate
# Configura tu conexión MySQL en .env
php artisan migrate
php artisan serve
```

## Tests

```bash
php artisan test
```

## Motivación

Proyecto desarrollado como práctica personal para reforzar conceptos de Laravel (migraciones, relaciones Eloquent, autorización basada en roles, testing) de cara a un proceso de selección para un puesto de desarrollo backend/fullstack junior.

---

*Proyecto en desarrollo activo.*
