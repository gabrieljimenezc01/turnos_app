<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['servicio_id'])) {
    $servicio_id = $_POST['servicio_id'];

    try {
        // Obtener el turno en espera más antiguo para el servicio especificado
        $stmt = $conn->prepare("SELECT id, numero FROM turnos WHERE servicio_id = ? AND estado = 'espera' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute([$servicio_id]);
        $turno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($turno) {
            // Actualizar el estado del turno a "atendido"
            $stmt = $conn->prepare("UPDATE turnos SET estado = 'atendido' WHERE id = ?");
            $stmt->execute([$turno['id']]);

            // Obtener el prefijo del servicio
            $stmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = ?");
            $stmt->execute([$servicio_id]);
            $servicio = $stmt->fetch(PDO::FETCH_ASSOC)['nombre'];
            $prefijo = strtoupper(substr($servicio, 0, 1));

            $siguiente_turno = $prefijo . ($turno['numero'] + 1);

            echo json_encode([
                "message" => "El turno ha sido avanzado.",
                "servicio" => $servicio,
                "siguiente_turno" => $siguiente_turno
            ]);
        } else {
            echo json_encode([
                "message" => "No hay turnos en espera para este servicio."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "message" => "Error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "message" => "Solicitud inválida."
    ]);
}
?>
