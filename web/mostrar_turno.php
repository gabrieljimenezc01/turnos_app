<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Turnos</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        // Script para actualizar la página cada 5 segundos
        setInterval(function(){
            window.location.reload();
        }, 5000);
    </script>
</head>
<body>
    <h1>Turnos en Espera</h1>
    <?php
    try {
        // Consulta para obtener los turnos que están en estado "espera"
        $stmt = $conn->query("SELECT c.nombre as caja, t.numero, cl.nombre as cliente, cl.telefono, cl.cedula 
                              FROM turnos t 
                              JOIN cajas c ON t.caja_id = c.id 
                              JOIN clientes cl ON t.cliente_cedula = cl.cedula 
                              WHERE t.estado = 'espera' 
                              ORDER BY t.created_at ASC");
        $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($turnos) {
            echo "<table>
                    <tr>
                      <th>Caja</th>
                      <th>Turno</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Cédula</th>
                    </tr>";
            foreach ($turnos as $turno) {
                echo "<tr>
                        <td>" . htmlspecialchars($turno['caja']) . "</td>
                        <td>" . htmlspecialchars($turno['numero']) . "</td>
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
