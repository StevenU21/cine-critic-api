# ✨ CineCritic API ✨

CineCritic API es una API RESTful para gestionar una plataforma de reseñas de películas. Permite a los usuarios registrarse, iniciar sesión, buscar películas, escribir reseñas, calificar películas y ver estadísticas detalladas sobre las mismas. 

---

## 📊 Características

### 🔐 **Autenticación y usuarios**
- Registro de usuarios con avatar opcional.
- Inicio de sesión y cierre de sesión con tokens (Laravel Sanctum).
- Recuperación de contraseña por correo electrónico.

### 🎥 **Gestor de películas**
- CRUD para películas con información como:
  - Título, descripción, director, año de lanzamiento, género y póster.
- Búsqueda y filtros por título, género o año.
- Relación de películas con múltiples géneros (tabla pivote).

### 🔹 **Reseñas de películas**
- Publicación de reseñas por parte de los usuarios.
- Edición y eliminación de reseñas propias.
- Calificaciones de películas (1 a 5 estrellas).
- Cálculo del promedio de calificaciones para cada película.

### 📢 **Notificaciones**
- Sistema de notificaciones en tiempo real:
  - Los usuarios reciben una notificación cuando alguien escribe una reseña en una película que ellos también han reseñado.

### 🔍 **Estadísticas**
- Películas más populares (más reseñadas o mejor calificadas).
- Contador de reseñas realizadas por usuario.


## 📄 Esquema de Base de Datos

### Principales tablas:
1. **Usuarios (`users`)**: Gestor de cuentas de usuario.
2. **Películas (`movies`)**: Almacena información de las películas.
3. **Géneros (`genres`)**: Lista de géneros disponibles.
4. **Relación Película-Género (`movie_genre`)**: Tabla pivote entre películas y géneros.
5. **Reseñas (`reviews`)**: Registra las reseñas de los usuarios.

---

## 🚀 Tecnologías Usadas

- **Laravel 11**: Framework para el desarrollo de la API.
- **Sanctum**: Autenticación basada en tokens.
- **MySQL**: Base de datos relacional.
- **Broadcasting**: Para notificaciones en tiempo real.
- **Postman**: Pruebas de la API.

---

## 🔄 Instalación

### Requisitos previos:
- PHP >= 8.2
- Composer
- MySQL/SQLITE
- LARAGON/XAMPP

### Pasos:

1. Clona el repositorio:
   ```bash
   git clone  https://github.com/StevenU21/CineCritic-API.git
   cd CineCritic-API
   ```

2. Instala las dependencias:
   ```bash
   composer install
   ```

3. Copia el archivo `.env.example` a `.env` y configura tus variables de entorno:
   ```bash
   cp .env.example .env
   ```

4. Genera la clave de aplicación:
   ```bash
   php artisan key:generate
   ```

5. Configura la base de datos en el archivo `.env` y migra las tablas:
   ```bash
   php artisan migrate
   ```

6. Genera datos de prueba:
   ```bash
   php artisan db:seed
   ```

7. Inicia el servidor local:
   ```bash
   php artisan serve
   ```
8. Ejecuta los tests:
   ```bash
   php artisan test
   ```

## 🌐 Recursos Adicionales
- [Laravel Sanctum Documentation](https://laravel.com/docs/10.x/sanctum)
- [Laravel Broadcasting](https://laravel.com/docs/10.x/broadcasting)
- [Postman](https://www.postman.com/)

---
