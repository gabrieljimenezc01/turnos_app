<?php
require 'db.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo "No autorizado";
    exit();
}

$servicio_id = $_SESSION['servicio_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM turnos WHERE servicio_id = :servicio_id AND estado = 'espera' ORDER BY created_at ASC LIMIT 1");
    $stmt->bindParam(':servicio_id', $servicio_id);
    $stmt->execute();
    $turno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($turno) {
        $updateStmt = $conn->prepare("UPDATE turnos SET estado = 'atendido' WHERE id = :id");
        $updateStmt->bindParam(':id', $turno['id']);
        $updateStmt->execute();

        // Obtener el nombre del servicio
        $servicioStmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = :servicio_id");
        $servicioStmt->bindParam(':servicio_id', $servicio_id);
        $servicioStmt->execute();
        $servicio = $servicioStmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'servicio' => $servicio['nombre'],
            'siguiente_turno' => strtoupper(substr($servicio['nombre'], 0, 1)) . ($turno['numero'] + 1)
        ]);
    } else {
        echo "No hay turnos en espera.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
