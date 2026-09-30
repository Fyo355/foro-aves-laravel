# 🐦 Foro de Aves

Foro comunitario para publicar y comentar avistamientos y contenido sobre aves, construido con **Laravel + React (Inertia)** como proyecto de práctica para reforzar conceptos fullstack: relaciones de base de datos, autenticación, autorización por roles, frontend con React y testing.

## Funcionalidades

| | Funcionalidad | Estado |
|---|---|---|
| 🗃️ | Modelo de datos: usuarios, especies, posts y comentarios con sus relaciones | ✅ Hecho |
| 🔐 | Registro, login, recuperación de contraseña y perfil (Breeze + React) | ✅ Hecho |
| 👤 | Roles de **usuario** y **administrador** (columna `role` + `User::isAdmin()`) | 🟡 Modelo hecho, permisos pendientes |
| 📝 | CRUD de posts categorizados por especie (solo el autor edita y borra) | ⏳ Pendiente |
| 💬 | Comentarios (solo usuarios registrados) | ⏳ Pendiente |
| 🛡️ | Panel de administrador: CRUD de usuarios y moderación | ⏳ Pendiente |
| 🖼️ | Foto de perfil (subida de archivos) | ⏳ Pendiente |
| 🗑️ | Usuarios eliminados: su contenido se conserva como "Usuario eliminado" (estilo Reddit) | ✅ Hecho a nivel de BD |
| ✅ | Tests con Pest | 🟡 Tests de autenticación y perfil |

## Stack

- **Backend**: Laravel 13, PHP 8.4
- **Frontend**: React 18 + [Inertia.js](https://inertiajs.com) 2, a partir del starter kit Laravel Breeze
- **Estilos**: Tailwind CSS 3
- **Build**: Vite 8
- **Base de datos**: SQLite
- **Testing**: Pest
- **Entorno local**: Laravel Herd (Windows)

### ¿Por qué Inertia?

Inertia permite construir el frontend con React **sin crear una API**. Los controladores de Laravel devuelven `Inertia::render('Pagina', $props)` en lugar de una vista Blade. La navegación funciona como una SPA (sin recargar la página), pero las rutas, la sesión, la validación y la autorización siguen en Laravel:

- En la **primera visita**, el servidor devuelve HTML (`resources/views/app.blade.php`) con los props incluidos, y React monta la página.
- En las **siguientes visitas** (`<Link>`, `useForm`), Inertia hace una petición XHR con la cabecera `X-Inertia` y el servidor responde solo con JSON: `{ component, props, url }`.

## Modelo de datos

```
users     → id, name, email, password, profile_photo, role (enum: user/admin)
species   → id, name
posts     → id, title, content, user_id (FK), species_id (FK)
comments  → id, content, user_id (FK), post_id (FK)
```

### Decisiones de diseño destacadas

- **`role` como ENUM** en lugar de valores numéricos: la base de datos garantiza que solo existan los roles `user` y `admin`, evitando datos inconsistentes. Además, `role` no es asignable en masa (no está en `#[Fillable]`), así que un usuario no puede cambiarse el rol desde un formulario.
- **`species` como tabla relacional** en lugar de texto libre: permite un buscador/filtro fiable por especie sin errores de escritura.
- **Borrado de usuario no destructivo**: los posts y comentarios de un usuario eliminado se conservan (`nullOnDelete`), mientras que los comentarios de un post eliminado sí se eliminan en cascada (`cascadeOnDelete`), ya que un comentario sin post no tiene sentido.

## Instalación local

Requisitos: PHP 8.4, Composer y Node 20 o superior (con [Laravel Herd](https://herd.laravel.com) ya vienen PHP y Composer).

```bash
git clone https://github.com/Fyo355/foro-aves-laravel.git
cd foro-aves-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed    # crea database/database.sqlite si no existe
npm install
npm run dev                   # o npm run build para compilar una vez
```

- **Con Herd**: si el proyecto está fuera de la carpeta de Herd, ejecuta `herd link` desde su carpeta y abre `http://foro-aves-laravel.test` (el nombre depende de la carpeta).
- **Sin Herd**: ejecuta `php artisan serve` en otra terminal y abre `http://localhost:8000`.

### Datos de prueba

El seeder crea tres usuarios (la contraseña está definida en `database/factories/UserFactory.php`) y 4 especies:

| Email | Rol |
|---|---|
| `admin@example.com` | admin |
| `test@example.com` | user |
| `john@example.com` | user |

## Tests

```bash
php artisan test
```

## Notas técnicas

Las plantillas de Breeze son anteriores a Laravel 13, así que al instalarlo hubo que hacer dos ajustes:

- `@vitejs/plugin-react` se subió a `^6`, porque la v4 que instala Breeze no admite Vite 8.
- Se quitó `import './bootstrap'` de `resources/js/app.jsx`: el skeleton de Laravel 13 ya no incluye ese archivo, que solo configuraba axios.

## Motivación

Proyecto desarrollado como práctica personal para reforzar conceptos de Laravel y React (migraciones, relaciones Eloquent, Inertia, autorización basada en roles, testing).

---

*Proyecto en desarrollo activo.*
