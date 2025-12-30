-- 1. Crear base de datos
CREATE DATABASE IF NOT EXISTS sgtech_rrhh_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Crear usuario
CREATE USER IF NOT EXISTS 'sgtech_app_user'@'%' IDENTIFIED BY 'SgTechP@ss2025';

-- 3. Asignar permisos
GRANT ALL PRIVILEGES ON sgtech_rrhh_app.* TO 'sgtech_app_user'@'%';
FLUSH PRIVILEGES;
