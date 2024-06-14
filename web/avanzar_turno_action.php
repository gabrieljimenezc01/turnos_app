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
} else {
    echo "Solicitud inválida.";
}
?>
