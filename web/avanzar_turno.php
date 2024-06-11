<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['servicio_id'])) {
    $servicio_id = $_POST['servicio_id'];

    try {
        // Obtener el turno en espera más antiguo para el servicio especificado
        $stmt = $conn->prepare("SELECT id FROM turnos WHERE servicio_id = ? AND estado = 'espera' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute([$servicio_id]);
        $turno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($turno) {
            // Actualizar el estado del turno a "atendido"
            $stmt = $conn->prepare("UPDATE turnos SET estado = 'atendido' WHERE id = ?");
            $stmt->execute([$turno['id']]);
            echo "El turno ha sido avanzado.";
        } else {
            echo "No hay turnos en espera para este servicio.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener la lista de servicios desde la base de datos
try {
    $stmt = $conn->query("SELECT id, nombre FROM servicios");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Avanzar Turno</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Avanzar Turno</h1>
    <form method="POST" action="avanzar_turno.php">
        <label for="servicio_id">Servicio:</label>
        <select id="servicio_id" name="servicio_id" required>
            <?php
            foreach ($servicios as $servicio) {
                echo "<option value='" . htmlspecialchars($servicio['id']) . "'>" . htmlspecialchars($servicio['nombre']) . "</option>";
            }
            ?>
        </select><br>

        <button type="submit">Avanzar Turno</button>
    </form>
</body>
</html>
