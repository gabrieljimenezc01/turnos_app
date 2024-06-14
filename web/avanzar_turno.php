<?php
require 'db.php';

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
                        alert(response);
                        // Disparar el evento de almacenamiento local para notificar la actualización
                        localStorage.setItem('turno_avanzado', new Date().getTime());
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
      <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Avanzar Turno</h1>
    <form id="avanzar_form">
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
