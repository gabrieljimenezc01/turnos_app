<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $caja_id = $_POST['caja_id'];

    try {
        // Inicia una transacción
        $conn->beginTransaction();

        // Inserta los datos del cliente
        $stmt = $conn->prepare("INSERT INTO clientes (nombre, telefono, email) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $telefono, $email]);
        $cliente_id = $conn->lastInsertId();

        // Obtén el número de turno para la caja específica
        $stmt = $conn->prepare("SELECT IFNULL(MAX(numero), 0) + 1 AS nuevo_turno FROM turnos WHERE caja_id = ?");
        $stmt->execute([$caja_id]);
        $nuevo_turno = $stmt->fetchColumn();

        // Inserta el turno asociando al cliente
        $stmt = $conn->prepare("INSERT INTO turnos (numero, caja_id, estado, cliente_id) VALUES (?, ?, 'espera', ?)");
        $stmt->execute([$nuevo_turno, $caja_id, $cliente_id]);

        // Confirma la transacción
        $conn->commit();

        echo "Turno solicitado correctamente. Su número de turno es " . $nuevo_turno;
    } catch (PDOException $e) {
        // Revierte la transacción en caso de error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Turno</title>
</head>
<body>
    <h1>Solicitar Turno</h1>
    <form method="POST" action="solicitar_turno.php">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="caja_id">Caja:</label>
        <select id="caja_id" name="caja_id" required>
            <option value="1">Caja 1</option>
            <option value="2">Caja 2</option>
            <option value="3">Caja 3</option>
            <!-- Añade más opciones según el número de cajas que tengas -->
        </select><br>

        <button type="submit">Solicitar Turno</button>
    </form>
</body>
</html>
