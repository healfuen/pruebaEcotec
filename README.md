# Proyecto de Sistema de Inscripción Académica

## Descripción

Este es un proyecto backend desarrollado en Laravel que gestiona un sistema académico. Incluye funcionalidades para gestionar estudiantes, cursos, inscripciones y autenticación de usuarios mediante JWT (JSON Web Tokens).

## Tecnologías Utilizadas

- **Framework:** Laravel 10
- **Base de Datos:** MySQL
- **Autenticación:** JWT (Tymon\JWTAuth)
- **Generación de Reportes:** DomPDF y Laravel Excel
- **Gestor de Dependencias:** Composer

## Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL
- Node.js y npm (opcional para el frontend relacionado)

## Instalación

1. Clona este repositorio:
   ```bash
   git clone https://github.com/healfuen/pruebaEcotec.git
   ```
2. Navega al directorio del proyecto:
   ```bash
   cd pruebaEcotec
   ```
3. Instala las dependencias de PHP:
   ```bash
   composer install
   ```
4. Configura el archivo `.env`:
   - Copia el archivo de ejemplo:
     ```bash
     cp .env.example .env
     ```
   - Configura la conexión a la base de datos en el archivo `.env`.
5. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
6. Ejecuta las migraciones y los seeders:
   ```bash
   php artisan migrate --seed
   ```
7. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

## Funcionalidades Principales

### Estudiantes

- Crear, editar, eliminar y listar estudiantes.
- Generar reportes en PDF con información de cursos inscritos.

### Cursos

- Crear, editar, eliminar y listar cursos.
- Validación de solapamientos de horarios y cupos disponibles.
- Exportar información de cursos y estudiantes inscritos en formato Excel.

### Inscripciones

- Inscribir estudiantes en cursos disponibles.
- Validar solapamientos de horarios antes de inscribir.
- Listar los cursos inscritos por un estudiante.

### Autenticación

- Registro y login de usuarios mediante JWT.
- Middleware para proteger rutas.

## Endpoints Principales

### Autenticación

- **POST** `/api/login`: Iniciar sesión.
- **POST** `/api/register`: Registrar un usuario.
- **POST** `/api/logout`: Cerrar sesión.

### Estudiantes

- **GET** `/api/estudiantes`: Listar todos los estudiantes.
- **POST** `/api/estudiantes`: Crear un estudiante.
- **PUT** `/api/estudiantes/{id}`: Actualizar un estudiante.
- **DELETE** `/api/estudiantes/{id}`: Eliminar un estudiante.
- **GET** `/api/inscripciones/reportePorEstudiante/{id}`: Generar un PDF con los cursos inscritos por un estudiante.

### Cursos

- **GET** `/api/cursos`: Listar todos los cursos.
- **POST** `/api/cursos`: Crear un curso.
- **PUT** `/api/cursos/{id}`: Actualizar un curso.
- **DELETE** `/api/cursos/{id}`: Eliminar un curso.
- **GET** `/api/cursos/cursos-disponibles/{estudianteId}`: Listar cursos disponibles para un estudiante.
- **GET** `/api/cursos/exportar`: Generar un archivo Excel con información de los cursos.

### Inscripciones

- **POST** `/api/inscripciones`: Inscribir un estudiante en un curso.
- **GET** `/api/inscripciones/cursos-inscritos/{estudianteId}`: Listar los cursos inscritos por un estudiante.

## Generación de Reportes

### PDF

Se utiliza DomPDF para generar reportes detallados por estudiante con información de los cursos inscritos, como código, nombre, aula y docente.

### Excel

Se utiliza Laravel Excel para exportar un archivo con la siguiente información:

- Datos generales del curso: código, nombre, aula y docente.
- Lista de estudiantes inscritos: matrícula y nombre.
- Estadísticas de inscripciones y cupos disponibles.

## Consideraciones

- Asegúrate de configurar correctamente las variables de entorno en el archivo `.env`.
- Para las rutas protegidas por JWT, incluye el token de autenticación en los encabezados:
  ```json
  {
      "Authorization": "Bearer <tu_token_jwt>"
  }
  ```

---

**Autor:** Héctor Fuentes Montenegro

