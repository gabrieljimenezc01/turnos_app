<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$servicio_id = $_SESSION['servicio_id'];

// Obtener el nombre del servicio
$stmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = :servicio_id");
$stmt->bindParam(':servicio_id', $servicio_id);
$stmt->execute();
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);
$servicio_nombre = $servicio['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Avanzar Turno</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#avanzarTurno").click(function() {
                $.ajax({
                    url: 'avanzar_turno_handler.php',
                    type: 'POST',
                    data: { servicio_id: <?= $servicio_id ?> },
                    success: function(response) {
                        alert(response);
                        localStorage.setItem('turno_avanzado', response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h1>Avanzar Turno - <?= htmlspecialchars($servicio_nombre) ?></h1>
    <button id="avanzarTurno">Avanzar Turno</button>
</body>
</html>
