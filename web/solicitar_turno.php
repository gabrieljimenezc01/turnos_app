<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $servicio_id = $_POST['servicio_id'];

    try {
        // Verificar si el cliente ya existe
        $stmt = $conn->prepare("SELECT cedula FROM clientes WHERE cedula = ?");
        $stmt->execute([$cedula]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            // Actualizar los datos del cliente existente
            $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, telefono = ? WHERE cedula = ?");
            $stmt->execute([$nombre, $telefono, $cedula]);
        } else {
            // Insertar los datos del cliente en la tabla de clientes
            $stmt = $conn->prepare("INSERT INTO clientes (cedula, nombre, telefono) VALUES (?, ?, ?)");
            $stmt->execute([$cedula, $nombre, $telefono]);
        }

        // Obtener el prefijo del servicio
        $stmt = $conn->prepare("SELECT nombre FROM servicios WHERE id = ?");
        $stmt->execute([$servicio_id]);
        $servicio = $stmt->fetch(PDO::FETCH_ASSOC)['nombre'];
        $prefijo = strtoupper(substr($servicio, 0, 1));

        // Obtener el número de turno más alto para el servicio especificado
        $stmt = $conn->prepare("SELECT IFNULL(MAX(numero), 0) + 1 as nuevo_turno FROM turnos WHERE servicio_id = ?");
        $stmt->execute([$servicio_id]);
        $nuevo_turno = $stmt->fetch(PDO::FETCH_ASSOC)['nuevo_turno'];

        // Insertar el nuevo turno en la tabla de turnos
        $stmt = $conn->prepare("INSERT INTO turnos (numero, servicio_id, estado, cliente_cedula) VALUES (?, ?, 'espera', ?)");
        $stmt->execute([$nuevo_turno, $servicio_id, $cedula]);

        $mensaje = "Turno solicitado correctamente. Su número de turno es " . $prefijo . $nuevo_turno;
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
    <title>Solicitar Turno</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cedula').on('blur', function() {
                var cedula = $(this).val();
                if (cedula) {
                    $.ajax({
                        url: 'obtener_cliente.php',
                        type: 'POST',
                        data: { cedula: cedula },
                        success: function(data) {
                            var cliente = JSON.parse(data);
                            if (cliente) {
                                $('#nombre').val(cliente.nombre);
                                $('#telefono').val(cliente.telefono);
                            } else {
                                $('#nombre').val('');
                                $('#telefono').val('');
                            }
                        }
                    });
                }
            });
        });
    </script>
     <link rel="stylesheet" type="text/css" href="styles.css">
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

        <label for="servicio_id">Servicio:</label>
        <select id="servicio_id" name="servicio_id" required>
            <?php
            foreach ($servicios as $servicio) {
                echo "<option value='" . htmlspecialchars($servicio['id']) . "'>" . htmlspecialchars($servicio['nombre']) . "</option>";
            }
            ?>
        </select><br>

        <button type="submit">Solicitar Turno</button>
    </form>
    <?php if ($mensaje): ?>
    <div class="mensaje">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
    <?php endif; ?>
</body>
</html>
