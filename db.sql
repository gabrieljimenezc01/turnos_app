USE turnos_db;

CREATE TABLE cajas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL
);

CREATE TABLE turnos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  caja_id INT,
  numero INT,
  estado ENUM('espera', 'atendido') DEFAULT 'espera',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (caja_id) REFERENCES cajas(id)
);

INSERT INTO cajas (nombre) VALUES ('Caja 1'), ('Caja 2'), ('Caja 3');

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL
);

ALTER TABLE turnos ADD COLUMN cliente_id INT;
ALTER TABLE turnos ADD CONSTRAINT fk_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id);

-- Eliminar la tabla de clientes si existe
DROP TABLE IF EXISTS clientes;

-- Crear la tabla de clientes con la cédula como clave primaria
CREATE TABLE clientes (
    cedula VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL
);

-- Modificar la tabla de turnos para referenciar la tabla de clientes por cédula
ALTER TABLE turnos DROP FOREIGN KEY fk_cliente;
ALTER TABLE turnos DROP COLUMN cliente_id;
ALTER TABLE turnos ADD COLUMN cliente_cedula VARCHAR(20);
ALTER TABLE turnos ADD CONSTRAINT fk_cliente FOREIGN KEY (cliente_cedula) REFERENCES clientes(cedula);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

INSERT INTO servicios (nombre) VALUES ('Deposito'), ('Retiro'), ('Servicios Publicos'), ('Asesoria'), ('Otros Servicios');

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cajero', 'asesor', 'otros') NOT NULL,
    servicio_id INT,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
);

ALTER TABLE usuarios DROP COLUMN rol;

