---

# CineCritic-API

Una API RESTful desarrollada en Laravel para la gestión de reseñas de películas, con autenticación utilizando Laravel Sanctum, control de roles de usuario (Admin, Moderator, Reviewer), manejo de excepciones personalizadas, uso de servicios, resources y policies, panel de administración con estadísticas, y gestión avanzada de roles y permisos.

## Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Endpoints](#endpoints)
  - [Autenticación](#autenticación)
  - [Géneros (Genres)](#géneros-genres)
  - [Directores (Directors)](#directores-directors)
  - [Películas (Movies)](#películas-movies)
  - [Reseñas (Reviews)](#reseñas-reviews)
  - [Notificaciones](#notificaciones)
  - [Usuarios y Perfiles](#usuarios-y-perfiles)
  - [Administración](#administración)
- [Validaciones](#validaciones)
- [Recursos y Policies](#recursos-y-policies)
- [Servicios Adicionales](#servicios-adicionales)
- [Roles de Usuario](#roles-de-usuario)

---

## Características

- **Autenticación** con Laravel Sanctum (registro, login y logout).
- **Gestión de películas**, incluyendo búsqueda y filtros por directores, géneros y años.
- **Gestión de reseñas** con creación, edición, y eliminación de reseñas para cada película.
- **Gestión de géneros y directores** mediante endpoints CRUD.
- **Notificaciones**: visualización, marcar como leídas y eliminación.
- **Panel de administración (Dashboard)** con estadísticas como:
  - Conteos generales.
  - Top películas, usuarios, géneros y directores.
  - Registros recientes.
- **Gestión de roles y permisos** (Admin, Moderator, Reviewer).
- Uso de **Resources** para formatear las respuestas.
- Uso de **Policies** para la autorización de acciones.
- **Manejo de excepciones personalizadas** para respuestas consistentes.
- **Servicios** (por ejemplo, `ImageService`) para el procesamiento de imágenes.

---

## Requisitos

- PHP 8.2
- [Composer](https://getcomposer.org/)
- [Laravel](https://laravel.com/) 11.x 
- Base de datos MySQL, SQLite

---

## Instalación

1. **Clona el repositorio:**

   ```bash
   git clone https://github.com/StevenU21/CineCritic-API.git
   ```

   ```bash
   cd CineCritic-API
   ```

2. **Instala las dependencias de Composer:**

   ```bash
   composer install
   ```

3. **Configura el archivo `.env`:**

   Copia el archivo de ejemplo y configura tus variables de entorno:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Realiza las migraciones y seeders (si los hay):**

   ```bash
   php artisan migrate --seed
   ```

5. **Instala Laravel Sanctum:**

   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

6. **Inicia el servidor de desarrollo:**

   ```bash
   php artisan serve
   ```

---

## Configuración

- **Autenticación:** La API utiliza Laravel Sanctum para manejar la autenticación de usuarios. Es importante configurar correctamente el middleware `auth:sanctum` para proteger las rutas.
- **Roles y Permisos:** Se han definido roles de usuario (Admin, Moderator, Reviewer) y se utilizan policies para autorizar acciones específicas.
- **Carga de imágenes:** Se utiliza un servicio (`Services/ImageService`) para la gestión de imágenes, el cual se encarga de validar y almacenar imágenes para directores, películas, etc.

---

## Endpoints

A continuación se muestra un resumen de los endpoints disponibles en la API. Todos los endpoints que requieren autenticación deben incluir el token generado durante el login en el encabezado de la petición.

### Autenticación

- **Registro de Usuario**

  ```http
  POST /api/register
  ```

  Endpoint para registrar nuevos usuarios.

- **Login**

  ```http
  POST /api/login
  ```

  Endpoint para iniciar sesión.

- **Logout**

  ```http
  POST /api/logout
  ```

  Endpoint para cerrar sesión. **Requiere autenticación.**

### Géneros (Genres)

- **Listado y Creación de Géneros**

  ```http
  GET /api/genres
  POST /api/genres
  GET /api/genres/{id}
  DELETE /api/genres/{id}
  ```

- **Actualización de Género**

  ```http
  PUT /api/genres/{genre}
  ```

  *Nota: Se utiliza una ruta personalizada para la actualización.*

### Directores (Directors)

- **Listado y Creación de Directores**

  ```http
  GET /api/directors
  POST /api/directors
  GET /api/directors/{id}
  DELETE /api/directors/{id}
  ```

- **Actualización de Director**

  ```http
  PUT /api/directors/{director}
  ```

### Películas (Movies)

- **Operaciones CRUD**

  ```http
  GET /api/movies
  POST /api/movies
  GET /api/movies/{id}
  DELETE /api/movies/{id}
  ```

- **Actualización de Película**

  ```http
  PUT /api/movies/{movie}
  ```

- **Búsqueda y Autocompletado**

  ```http
  GET /api/movies/search
  GET /api/movies/search/auto-complete
  ```

- **Filtros para Películas**

  ```http
  GET /api/movies/filters/directors
  GET /api/movies/filters/genres
  GET /api/movies/filters/years
  ```

### Reseñas (Reviews)

- **Reseñas Generales**

  ```http
  GET /api/reviews
  GET /api/reviews/{review}
  ```

- **Reseñas por Película**

  ```http
  GET /api/reviews/movies/{movie}       // Listado de reseñas para una película.
  POST /api/reviews/movies/{movie}      // Crear una reseña para una película.
  PUT /api/reviews/movies/{movie}/{review} // Actualizar una reseña.
  DELETE /api/reviews/{review}          // Eliminar una reseña.
  ```

### Notificaciones

- **Listado y Gestión de Notificaciones**

  ```http
  GET /api/notifications
  PUT /api/notifications/{notification}/mark-as-read
  PUT /api/notifications/mark-all-as-read
  DELETE /api/notifications/{notification}
  DELETE /api/notifications
  ```

### Usuarios y Perfiles

- **Usuarios (Acceso para Administradores)**

  ```http
  GET /api/users
  GET /api/users/{user}
  ```

- **Perfil de Usuario**

  ```http
  GET /api/user-profile
  GET /api/user-profile/{user}
  ```

### Administración (Rutas protegidas con rol `admin`)

- **Gestión de Roles**

  ```http
  GET /api/admin/roles
  PUT /api/admin/roles/{user}/assign-role
  ```

- **Gestión de Permisos**

  ```http
  GET /api/admin/permissions
  GET /api/admin/permissions/{user}/list-permission
  POST /api/admin/permissions/{user}/give-permission
  DELETE /api/admin/permissions/{user}/revoke-permission
  ```

- **Dashboard y Estadísticas**

  ```http
  GET /api/admin/dashboard/counts
  GET /api/admin/dashboard/top-rated-movies
  GET /api/admin/dashboard/top-users
  GET /api/admin/dashboard/top-genres
  GET /api/admin/dashboard/top-directors
  GET /api/admin/dashboard/recent-movies
  GET /api/admin/dashboard/recent-reviews
  GET /api/admin/dashboard/recent-users
  ```

---

## Validaciones

Se han creado Request classes para validar las entradas de los distintos endpoints. Algunos ejemplos:

### DirectorRequest

```php
public function rules(): array
{
    return [
        'name'         => ['required', 'string', 'min:3', 'max:30'],
        'biography'    => ['required', 'string', 'min:3', 'max:2000'],
        'image'        => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:4096'],
        'birth_date'   => ['required', 'date', 'before:today', 'after:01-01-1890', 'date_format:d-m-Y'],
        'nationality'  => ['required', 'string', 'max:50'],
    ];
}
```

### GenreRequest

```php
public function rules(): array
{
    return [
        'name'        => ['required', 'string', 'min:6', 'max:30', Rule::unique('genres')->ignore($this->genre)],
        'description' => ['required', 'string', 'min:6', 'max:255'],
    ];
}
```

### LoginRequest

```php
public function rules(): array
{
    return [
        'email'    => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];
}
```

### MovieRequest

```php
public function rules(): array
{
    return [
        'title'       => ['required', 'string', 'min:6', 'max:60', Rule::unique('movies')->ignore($this->movie)],
        'description' => ['required', 'string', 'min:10', 'max:1000'],
        'cover_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        'release_date'=> ['required', 'date', 'before:today', 'date_format:Y-m-d'],
        'trailer_url' => ['required', 'url'],
        'duration'    => ['required', 'integer'],
    ];
}
```

### ReviewRequest

```php
public function rules(): array
{
    return [
        'content' => ['required', 'string', 'min:10', 'max:1000'],
        'rating'  => ['required', 'numeric', 'min:1', 'max:5']
    ];
}
```

---

## Recursos y Policies

- **Resources:**  
  Se utilizan Resources para formatear las respuestas de la API, asegurando una estructura consistente en los JSON devueltos.

- **Policies:**  
  Se implementan Policies para autorizar acciones en modelos, de forma que se pueda controlar qué usuarios pueden editar, eliminar o ver determinados recursos.

---

## Servicios Adicionales

- **ImageService:**  
  Un servicio dedicado a la manipulación y almacenamiento de imágenes, utilizado en endpoints que requieren subir imágenes (por ejemplo, en la creación de directores o películas).

---

## Roles de Usuario

La API maneja diferentes roles de usuario, cada uno con distintos niveles de permisos:

- **Admin:**  
  Tiene acceso total a las funcionalidades de administración, incluyendo la gestión de roles, permisos y dashboard.

- **Moderator:**  
  Puede gestionar contenidos y revisar reseñas, dependiendo de las políticas definidas.

- **Reviewer:**  
  Puede crear y editar sus reseñas, y acceder a funcionalidades básicas de usuario.

*La asignación y verificación de estos roles se realiza mediante middleware y policies, protegiendo las rutas sensibles.*

---
