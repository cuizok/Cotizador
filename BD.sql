
CREATE DATABASE IF NOT EXISTS Cotizador;

USE Cotizador;

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    empresa VARCHAR(100),
    correo VARCHAR(100),
    telefono VARCHAR(20),
    estatus ENUM('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from clientes;

CREATE TABLE cotizaciones(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    titulo VARCHAR(150),
    descripcion TEXT,
    estatus ENUM('BORRADOR','ENVIADA','ACEPTADA','RECHAZADA') DEFAULT 'BORRADOR',
    costo_total DECIMAL(10,2),
    tiempo_total_minutos INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_cliente)
    REFERENCES clientes(id)
);

select * from Cotizaciones;

CREATE TABLE detalle_cotizacion(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cotizacion INT,

    servicio VARCHAR(150),
    descripcion TEXT,

    costo DECIMAL(10,2),
    cantidad INT,

    tiempo INT,
    unidad_tiempo ENUM(
        'MINUTOS',
        'HORAS',
        'DIAS',
        'SEMANAS',
        'MESES',
        'ANIOS'
    ),

    subtotal DECIMAL(10,2),
    minutos_equivalentes INT,

    FOREIGN KEY(id_cotizacion)
    REFERENCES cotizaciones(id)
);

SELECT * FROM detalle_cotizacion;


INSERT INTO clientes
(
    nombre,
    empresa,
    correo,
    telefono,
    estatus
)
VALUES
(
    'Juan Pérez',
    'JP Solutions',
    'juan.perez@gmail.com',
    '4771234567',
    'ACTIVO'
),
(
    'María López',
    'Innovatech',
    'maria.lopez@gmail.com',
    '4779876543',
    'ACTIVO'
),
(
    'Carlos Hernández',
    'CH Consultores',
    'carlos.hernandez@gmail.com',
    '4775554433',
    'INACTIVO'
),
(
    'Ana Martínez',
    'AM Software',
    'ana.martinez@gmail.com',
    '4772223344',
    'ACTIVO'
),
(
    'Luis García',
    'LG Servicios',
    'luis.garcia@gmail.com',
    '4778887766',
    'ACTIVO'
);

ALTER TABLE cotizaciones
ADD COLUMN updated_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP
ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE detalle_cotizacion
ADD COLUMN updated_at TIMESTAMP
DEFAULT CURRENT_TIMESTAMP
ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE cotizaciones
MODIFY costo_total DECIMAL(10,2) DEFAULT 0;

ALTER TABLE cotizaciones
MODIFY tiempo_total_minutos INT DEFAULT 0;

ALTER TABLE detalle_cotizacion
DROP COLUMN cantidad;


ALTER TABLE detalle_cotizacion
DROP COLUMN subtotal,
DROP COLUMN minutos_equivalentes;

INSERT INTO usuarios
(
    nombre,
    correo,
    password
)
VALUES
(
    'C.RAMIREZ',
    'cuitlahuac0920@gmail.com',
    '$2y$10$FN3ReC3iv3QrCQbIpq9Rk.E6IXuHgCFGZ75JGx8ivGHlSkc8OIi8i'
);



