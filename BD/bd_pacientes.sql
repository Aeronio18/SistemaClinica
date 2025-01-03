-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS clinica_db;

-- Usar la base de datos recién creada
USE clinica_db;

-- Crear tabla para los usuarios (administradores y cajeros)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('Administrador', 'Cajero') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla para los pacientes
CREATE TABLE IF NOT EXISTS pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    age INT NOT NULL,
    gender ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    occupation VARCHAR(255) NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    allergies VARCHAR(255) NULL,    
    codigo_unico VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla para registrar visitas
CREATE TABLE IF NOT EXISTS visitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    fecha_visita TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    registrado_por INT NOT NULL,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id)
);

-- Crear tabla para beneficios de membresías
CREATE TABLE IF NOT EXISTS beneficios_membresia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_membresia ENUM('Basico', 'Bronce', 'Plata', 'Oro', 'Platino') NOT NULL,
    beneficio TEXT NOT NULL
);

-- Insertar datos iniciales en beneficios_membresia
INSERT INTO beneficios_membresia (tipo_membresia, beneficio) VALUES
('Basico', '5% de descuento en medicamentos genéricos'),
('Bronce', '5% de descuento en medicamentos genéricos, 1 consulta médica gratuita al año'),
('Plata', '10% de descuento en medicamentos genéricos, 2 consultas médicas gratuitas al año'),
('Oro', '15% de descuento en medicamentos genéricos, acceso prioritario en consultas, 3 consultas gratuitas al año'),
('Platino', '20% de descuento en medicamentos genéricos, acceso VIP, consultas médicas ilimitadas');

-- Insertar un usuario administrador por defecto
INSERT INTO usuarios (nombre_usuario, password, tipo_usuario) VALUES
('admin', 'admin123', 'Administrador');
