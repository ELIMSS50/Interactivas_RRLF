# Recetario

Sistema sencillo para registrar y gestionar recetas personales. Cada usuario solo ve y administra sus propias recetas.

## Requisitos

- PHP 8.3 o superior con la extensión `pdo_mysql`
- Composer
- Node.js y npm
- MySQL 8

## 1. Crear la base de datos

Entrar a MySQL como root:

```bash
mysql -u root -p
```

Ejecutar:

```sql
CREATE DATABASE recetario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'recetario'@'localhost' IDENTIFIED BY 'Recetario_2026';
GRANT ALL PRIVILEGES ON recetario.* TO 'recetario'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

> Las contraseñas de Mysql normalmente deben de contener mayusculas minusculas y numeros para que puedan funcionar al menos en la parte de usuarios para el sistema no es necesario.

## 2. Instalar el proyecto

```bash
composer install
npm install
php artisan key:generate
```

>Recordar crear/copiar las credenciales al .env

## 3. Configurar `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recetario
DB_USERNAME=recetario
DB_PASSWORD=Recetario_2026
```

## 4. Crear las tablas y compilar estilos

```bash
php artisan migrate
npm run build
```

## 5. Ejecutar

```bash
php artisan serve
```

Abrir http://localhost:8000, registrarse y puede comenzar a crear sus recetas.
