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

