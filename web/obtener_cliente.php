<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];

    try {
        // Obtener los datos del cliente
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE cedula = ?");
        $stmt->execute([$cedula]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($cliente);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
}
?>
