<?php
require 'db.php';

try {
    $stmt = $conn->query("SELECT s.nombre as servicio, CONCAT(SUBSTRING(s.nombre, 1, 1), t.numero) as turno FROM turnos t JOIN servicios s ON t.servicio_id = s.id WHERE t.estado = 'espera' ORDER BY t.created_at ASC");
    $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($turnos) {
        echo "<table>
                <tr>
                  <th>Servicio</th>
                  <th>Turno</th>
                </tr>";
        foreach ($turnos as $turno) {
            echo "<tr>
                    <td>" . htmlspecialchars($turno['servicio']) . "</td>
                    <td>" . htmlspecialchars($turno['turno']) . "</td>
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
