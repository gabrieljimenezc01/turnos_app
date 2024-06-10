<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $caja_id = $_POST['caja_id'];

    try {
        // Verificar si el cliente ya existe
        $stmt = $conn->prepare("SELECT cedula FROM clientes WHERE cedula = ?");
        $stmt->execute([$cedula]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            // Insertar los datos del cliente en la tabla de clientes
            $stmt = $conn->prepare("INSERT INTO clientes (cedula, nombre, telefono) VALUES (?, ?, ?)");
            $stmt->execute([$cedula, $nombre, $telefono]);
        }

        // Obtener el número de turno más alto para la caja especificada
        $stmt = $conn->prepare("SELECT IFNULL(MAX(numero), 0) + 1 as nuevo_turno FROM turnos WHERE caja_id = ?");
        $stmt->execute([$caja_id]);
        $nuevo_turno = $stmt->fetch(PDO::FETCH_ASSOC)['nuevo_turno'];

        // Insertar el nuevo turno en la tabla de turnos
        $stmt = $conn->prepare("INSERT INTO turnos (numero, caja_id, estado, cliente_cedula) VALUES (?, ?, 'espera', ?)");
        $stmt->execute([$nuevo_turno, $caja_id, $cedula]);

        echo "Turno solicitado correctamente. Su número de turno es " . $nuevo_turno;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener la lista de cajas desde la base de datos
try {
    $stmt = $conn->query("SELECT id, nombre FROM cajas");
    $cajas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>

        <label for="caja_id">Caja:</label>
        <select id="caja_id" name="caja_id" required>
            <?php
            foreach ($cajas as $caja) {
                echo "<option value='" . htmlspecialchars($caja['id']) . "'>" . htmlspecialchars($caja['nombre']) . "</option>";
            }
            ?>
        </select><br>

        <button type="submit">Solicitar Turno</button>
    </form>
</body>
</html>
