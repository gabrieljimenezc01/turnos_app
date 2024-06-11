<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Turnos</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Turnos en Espera</h1>
    <?php
    try {
        // Consulta para obtener los turnos que están en estado "espera"
        $stmt = $conn->query("SELECT s.nombre as servicio, t.numero, cl.nombre as cliente, cl.telefono, cl.cedula 
                              FROM turnos t 
                              JOIN servicios s ON t.servicio_id = s.id 
                              JOIN clientes cl ON t.cliente_cedula = cl.cedula 
                              WHERE t.estado = 'espera' 
                              ORDER BY t.created_at ASC");
        $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($turnos) {
            echo "<table>
                    <tr>
                      <th>Servicio</th>
                      <th>Turno</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Cédula</th>
                    </tr>";
            foreach ($turnos as $turno) {
                $prefijo = strtoupper(substr($turno['servicio'], 0, 1));
                echo "<tr>
                        <td>" . htmlspecialchars($turno['servicio']) . "</td>
                        <td>" . htmlspecialchars($prefijo . $turno['numero']) . "</td>
                        <td>" . htmlspecialchars($turno['cliente']) . "</td>
                        <td>" . htmlspecialchars($turno['telefono']) . "</td>
                        <td>" . htmlspecialchars($turno['cedula']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay turnos en espera.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>
