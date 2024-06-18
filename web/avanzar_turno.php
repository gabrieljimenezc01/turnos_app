<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
};

$servicio_id_session = $_SESSION['servicio_id'];
echo $servicio_id_session." id del servicio de session";
// Obtener la lista de servicios desde la base de datos
try {
    $stmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = :iduser");
    $stmt->bindParam(':iduser', $servicio_id_session);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
echo "nombre del servicio: ".$user['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Avanzar Turno</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#avanzar_form').on('submit', function(e) {
                e.preventDefault();
                var servicio_id = $('#servicio_id').val();
                
                $.ajax({
                    url: 'avanzar_turno_action.php',
                    type: 'POST',
                    data: { servicio_id: servicio_id },
                    success: function(response) {
                        var data = JSON.parse(response);
                        alert(data.message);
                        // Disparar el evento de almacenamiento local para notificar la actualización
                        localStorage.setItem('turno_avanzado', JSON.stringify({
                            timestamp: new Date().getTime(),
                            servicio: data.servicio,
                            siguiente_turno: data.siguiente_turno
                        }));
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
    <h1>Avanzar Turno</h1>
    <form id="avanzar_form">
        <label for="servicio_id">Servicio:</label>
        <select id="servicio_id" name="servicio_id" required>
            <?php
                echo "<option value='" . htmlspecialchars($servicio_id_session) . "'>" . htmlspecialchars($user['nombre']) . "</option>";
            
            ?>
        </select><br>

        <button type="submit">Avanzar Turno</button>
    </form>
</body>
</html>
