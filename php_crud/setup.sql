-- setup.sql
-- Ejecuta este script en phpMyAdmin o en la terminal MySQL de XAMPP
-- para crear la base de datos y las tablas necesarias.

CREATE DATABASE IF NOT EXISTS crud_auth
    CHARACTER SET utf8
    COLLATE utf8_general_ci;

USE crud_auth;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100)   NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

-- (Opcional) Usuario de prueba: usuario=admin, contraseña=password
-- El hash fue generado con password_hash('password', PASSWORD_BCRYPT)
INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = username;
