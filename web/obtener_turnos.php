<?php
require 'db.php';

try {
    // Obtener turnos en espera junto con el nombre del cliente usando la columna cliente_cedula
    $stmt = $conn->query("SELECT s.nombre as servicio, CONCAT(UPPER(SUBSTRING(s.nombre, 1, 1)), t.numero) as turno, c.nombre as cliente 
                          FROM turnos t 
                          JOIN servicios s ON t.servicio_id = s.id 
                          JOIN clientes c ON t.cliente_cedula = c.cedula 
                          WHERE t.estado = 'espera' 
                          ORDER BY t.created_at ASC");
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($turnos) {
        echo "<table>
                <tr>
                  <th>Servicio</th>
                  <th>Turno</th>
                  <th>Cliente</th>
                </tr>";
        foreach ($turnos as $turno) {
            echo "<tr>
                    <td>" . htmlspecialchars($turno['servicio']) . "</td>
                    <td>" . htmlspecialchars($turno['turno']) . "</td>
                    <td>" . htmlspecialchars($turno['cliente']) . "</td>
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
