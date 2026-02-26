# Document Register - Sistema de Registro de Documentos

![CI Tests](https://github.com/saulmoralespa/document-register/workflows/CI%20-%20Tests/badge.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.4+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Symfony](https://img.shields.io/badge/Symfony-8.0-black)

Sistema CRUD para gestión de documentos con generación automática de códigos únicos basado en Symfony 8.

---

## 📑 Tabla de Contenidos

1. [Inicio Rápido](#-inicio-rápido)
2. [Requisitos Previos](#-requisitos-previos)
3. [Instalación y Configuración](#-instalación-y-configuración)
   - [Clonar el repositorio](#1-clonar-el-repositorio-o-descargar-el-código)
   - [Instalar dependencias](#2-instalar-dependencias-de-php)
   - [Configurar variables de entorno](#3-configurar-variables-de-entorno)
   - [Iniciar base de datos](#4-iniciar-la-base-de-datos-con-docker)
   - [Crear BD y migraciones](#5-crear-la-base-de-datos-y-ejecutar-migraciones)
   - [Iniciar servidor](#6-iniciar-el-servidor-de-desarrollo)
   - [Acceder a la aplicación](#7-acceder-a-la-aplicación)
4. [Credenciales de Acceso](#-credenciales-de-acceso)
5. [Estructura de la Base de Datos](#-estructura-de-la-base-de-datos)
6. [Funcionalidades](#-funcionalidades)
7. [Comandos Útiles](#-comandos-útiles)
   - [Base de datos](#base-de-datos)
   - [Docker](#docker)
   - [Symfony](#symfony)
8. [CI/CD - Integración Continua](#-cicd---integración-continua)
9. [Ejemplos de Uso](#-ejemplos-de-uso)
10. [Solución de Problemas](#-solución-de-problemas)
11. [Tecnologías Utilizadas](#-tecnologías-utilizadas)
12. [Licencia](#-licencia)

---

## ⚡ Inicio Rápido

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar variables de entorno
cp .env.local.example .env.local

# 3. Iniciar base de datos con Docker
docker compose up -d

# 4. Esperar a que MySQL esté listo (15-20 segundos)
sleep 20

# 5. Ejecutar migraciones (la base de datos 'app' se crea automáticamente)
php bin/console doctrine:migrations:migrate --no-interaction

# 6. Cargar datos iniciales (usuario admin, procesos y tipos)
php bin/console doctrine:fixtures:load --no-interaction

# 7. Iniciar servidor de desarrollo
php -S localhost:8000 -t public/

# 8. Acceder a http://localhost:8000
# Usuario: admin | Contraseña: admin123
```

---

## 📋 Requisitos Previos

- PHP 8.4 o superior
- Composer
- Docker y Docker Compose
- Git (opcional)

## 🚀 Instalación y Configuración

### 1. Clonar el repositorio (o descargar el código)

```bash
git clone https://github.com/saulmoralespa/document-register
cd document-register
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Configurar variables de entorno

El archivo `.env.local` no está incluido en el repositorio por seguridad. Debes crearlo a partir del archivo de ejemplo:

```bash
# Copiar el archivo de ejemplo
cp .env.local.example .env.local
```

El archivo `.env.local` contiene la configuración para desarrollo con Docker:

```env
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3308/app?serverVersion=8.0&charset=utf8mb4"
APP_ENV=dev
APP_SECRET=your-secret-key-here-change-in-production
MAILER_DSN=smtp://localhost:1025
```

**Nota importante**: 
- El puerto es **3308** porque Docker mapea el puerto interno 3306 del contenedor MySQL al puerto 3308 del host (ver `compose.override.yaml`).
- Genera un nuevo `APP_SECRET` para producción con: `php bin/console secrets:generate-keys` o usa un generador de strings aleatorios.

### 4. Iniciar la base de datos con Docker

El proyecto usa Docker Compose para levantar los siguientes servicios:

- **MySQL 8.0**: Puerto **3308** (host) → 3306 (contenedor) - Base de datos
- **Mailpit**: Puerto 8025 (interfaz web) y 1025 (SMTP) - Servidor de correos de prueba

```bash
docker compose up -d
```

Verifica que los contenedores estén corriendo:

```bash
docker compose ps
```

Deberías ver algo como:

```
NAME                          COMMAND                  SERVICE    STATUS
document-register-database-1  "docker-entrypoint.s…"   database   Up
document-register-mailer-1    "/mailpit"               mailer     Up
```

### 5. Crear la base de datos y ejecutar migraciones

**Nota:** La base de datos `app` se crea automáticamente al iniciar el contenedor Docker, por lo que no necesitas ejecutar `doctrine:database:create`.

```bash
# Ejecutar las migraciones (crear tablas)
php bin/console doctrine:migrations:migrate --no-interaction

# Cargar datos iniciales (usuario admin, 5 procesos y 5 tipos de documentos)
php bin/console doctrine:fixtures:load --no-interaction
```

**Datos que se cargan:**
- ✅ 1 usuario admin (admin/admin123)
- ✅ 5 Procesos: Ingeniería, Recursos Humanos, Finanzas, Operaciones, Calidad
- ✅ 5 Tipos de Documentos: Instructivo, Procedimiento, Manual, Formato, Registro

### 6. Iniciar el servidor de desarrollo

```bash
symfony server:start
```

O si no tienes Symfony CLI instalado:

```bash
php -S localhost:8000 -t public/
```

### 7. Acceder a la aplicación

Abre tu navegador en: **http://localhost:8000**

---

## 🔐 Credenciales de Acceso

El sistema requiere autenticación para acceder. Los usuarios están almacenados en la base de datos MySQL.

### Credenciales de prueba:
- **Usuario**: `admin`
- **Contraseña**: `admin123`

### Acceso a la aplicación:
1. Abre tu navegador en: **http://localhost:8000**
2. Serás redirigido a la página de login (`/login`)
3. Ingresa las credenciales de prueba
4. Serás redirigido a la página principal de documentos

### Usuario en Base de Datos:
El usuario `admin` se crea automáticamente al ejecutar los fixtures:
```bash
php bin/console doctrine:fixtures:load
```

**Nota**: Los usuarios están almacenados en la tabla `users` de MySQL, con contraseñas hasheadas usando bcrypt. El password NO está hardcodeado en el código, se carga desde la base de datos.

---

## 📊 Estructura de la Base de Datos

El sistema utiliza tres tablas principales:

### PRO_PROCESO
Tabla de procesos precargada con 5 registros:

| PRO_ID | PRO_NOMBRE | PRO_PREFIJO |
|--------|------------|-------------|
| 1 | Ingeniería | ING |
| 2 | Recursos Humanos | RH |
| 3 | Finanzas | FIN |
| 4 | Operaciones | OPE |
| 5 | Calidad | CAL |

### TIP_TIPO_DOC
Tabla de tipos de documentos precargada con 5 registros:

| TIP_ID | TIP_NOMBRE | TIP_PREFIJO |
|--------|------------|-------------|
| 1 | Instructivo | INS |
| 2 | Procedimiento | PRO |
| 3 | Manual | MAN |
| 4 | Formato | FOR |
| 5 | Registro | REG |

### DOC_DOCUMENTO
Tabla principal de documentos con los siguientes campos:

- **DOC_ID**: ID autoincremental
- **DOC_NOMBRE**: Nombre del documento (máx. 60 caracteres)
- **DOC_CODIGO**: Código único generado automáticamente (formato: `TIP_PREFIJO-PRO_PREFIJO-<consecutivo>`)
- **DOC_CONTENIDO**: Contenido del documento (máx. 4000 caracteres)
- **DOC_ID_TIPO**: Relación con TIP_TIPO_DOC
- **DOC_ID_PROCESO**: Relación con PRO_PROCESO

---

## 🎯 Funcionalidades

### ✅ Gestión de Documentos

- **Crear documento**: El sistema genera automáticamente un código único basado en el tipo y proceso seleccionado
  - Ejemplo: `INS-ING-1` (Instructivo de Ingeniería, consecutivo 1)
  
- **Listar documentos**: Visualiza todos los documentos en una tabla/grilla

- **Buscar documentos**: Permite buscar por nombre o código

- **Editar documento**: Al cambiar el tipo o proceso, el código se recalcula automáticamente

- **Eliminar documento**: Elimina registros de documentos

### 🔒 Autenticación

- **Login**: Acceso con usuario y contraseña (en memoria)
- **Logout**: Cierre de sesión seguro
- **Protección de rutas**: Todas las rutas requieren autenticación excepto `/login`

---

## 🔧 Comandos Útiles

### Base de datos

```bash
# Ver el estado de las migraciones
php bin/console doctrine:migrations:status

# Ver las entidades mapeadas
php bin/console doctrine:mapping:info

# Validar el esquema de la base de datos
php bin/console doctrine:schema:validate

# Ver la estructura SQL de las tablas
php bin/console doctrine:schema:update --dump-sql
```

### Docker

```bash
# Iniciar los contenedores
docker compose up -d

# Detener los contenedores
docker compose down

# Ver logs de la base de datos
docker compose logs database

# Acceder al contenedor de MySQL
docker compose exec database mysql -u app -p
# Password: !ChangeMe!

# Ver contenedores en ejecución
docker compose ps
```

### Symfony

```bash
# Limpiar caché
php bin/console cache:clear

# Ver todas las rutas
php bin/console debug:router

# Ver servicios del contenedor
php bin/console debug:container

# Ver configuración de seguridad
php bin/console debug:security
```

---

## 🔄 CI/CD - Integración Continua

El proyecto incluye configuración de **GitHub Actions** para ejecutar automáticamente:

### ✅ Tests Automatizados
- Tests unitarios (Entidades, Servicios)
- Tests de integración (Repositorios)
- Tests funcionales (Controladores)
- **85 tests con 189 assertions**

### ✅ Verificaciones de Calidad
- Validación de sintaxis PHP
- Validación de esquema Doctrine
- Audit de seguridad de dependencias (composer audit)

### 📊 Ejecución del CI
El CI se ejecuta automáticamente en:
- Push a ramas `main`, `master`, `develop`
- Pull Requests a estas ramas

### 🚀 Ejecutar Tests Localmente

```bash
# Todos los tests
php bin/phpunit

# Con formato testdox (más legible)
php bin/phpunit --testdox

# Tests por categoría
php bin/phpunit tests/Entity/       # Tests unitarios
php bin/phpunit tests/Service/      # Tests de servicios
php bin/phpunit tests/Repository/   # Tests de repositorios
php bin/phpunit tests/Controller/   # Tests funcionales
```

**Configuración completa en:** `.github/workflows/ci.yml`

---

## 📖 Ejemplos de Uso

### Crear un documento

1. Inicia sesión con `admin` / `admin123`
2. Haz clic en "Nuevo Documento"
3. Completa el formulario:
   - **Nombre**: INSTRUCTIVO DE DESARROLLO
   - **Tipo**: Instructivo (INS)
   - **Proceso**: Ingeniería (ING)
   - **Contenido**: Descripción del instructivo...
4. El sistema generará automáticamente el código: `INS-ING-1`
5. Guarda el documento

### Editar un documento

1. Desde la lista de documentos, haz clic en "Editar"
2. Si cambias el **Tipo** a "Manual (MAN)" o el **Proceso** a "Calidad (CAL)"
3. El código se recalculará automáticamente (ej: `MAN-CAL-1`)

### Buscar documentos

1. En la página principal, usa el campo de búsqueda
2. Escribe parte del nombre o código del documento
3. Haz clic en "Buscar"
4. El sistema mostrará todos los documentos que coincidan

### Eliminar un documento

1. Desde la lista de documentos, haz clic en "Eliminar"
2. Confirma la acción en el diálogo de confirmación
3. El documento será eliminado permanentemente

---

## 🐛 Solución de Problemas

### Archivo .env.local no encontrado

Si al clonar el repositorio ves errores de configuración:

**Solución:**
```bash
# Crear el archivo .env.local desde la plantilla
cp .env.local.example .env.local
```

**Nota:** El archivo `.env.local` está en `.gitignore` y NO se incluye en el repositorio por seguridad.

### El contenedor de MySQL no inicia

```bash
# Verificar si el puerto 3308 está en uso
lsof -i :3308

# Ver logs del contenedor
docker compose logs database

# Reiniciar contenedores
docker compose down && docker compose up -d
```

### Error de conexión a la base de datos

Verifica que:
1. El contenedor esté corriendo: `docker compose ps`
2. Las credenciales en `.env.local` sean correctas
3. El puerto sea **3308** (no 3306)
4. MySQL esté listo (espera 15-20 segundos después de iniciar)

Para conectarte directamente a MySQL:
```bash
# Desde el host
mysql -h 127.0.0.1 -P 3308 -u app -p'!ChangeMe!'

# O desde dentro del contenedor
docker compose exec database mysql -u app -p'!ChangeMe!' app
```

### Reiniciar todo desde cero

```bash
# Detener y eliminar contenedores y volúmenes
docker compose down -v

# Iniciar contenedores
docker compose up -d

# Esperar a que MySQL esté listo
sleep 20

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate --no-interaction

# Cargar datos iniciales
php bin/console doctrine:fixtures:load --no-interaction
```

---

## 📦 Tecnologías Utilizadas

- **Symfony 8.0** - Framework PHP
- **Doctrine ORM** - Mapeo objeto-relacional
- **MySQL 8.0** - Base de datos
- **Docker** - Contenedorización
- **Twig** - Motor de plantillas
- **Symfony Security** - Autenticación y autorización

---

## 📄 Licencia

Proyecto propietario para uso educativo/demostrativo.


---

**Desarrollado con Symfony 8** 🎵


