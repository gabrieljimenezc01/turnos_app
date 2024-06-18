<?php
require 'db.php';
require 'encryption.php'; // Include encryption functions

$key = 'this_is_a_very_secure_key'; // Use the same secret key for encryption and decryption

// Datos de los usuarios a crear
$users = [
    ['username' => 'cajero1', 'password' => 'password1', 'servicio' => '40'],
    ['username' => 'cajero', 'password' => '123', 'servicio' => '40'],
    ['username' => 'asesor1', 'password' => 'password1', 'servicio' => '41']
];

try {
    // Inserta los servicios si no existen
    $servicios = ['cajero', 'asesor', 'otros'];
    foreach ($servicios as $servicio) {
        $stmt = $conn->prepare("INSERT IGNORE INTO servicios (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $servicio);
        $stmt->execute();
    }

    // Obtener los IDs de los servicios
    $stmt = $conn->query("SELECT id, nombre FROM servicios");
    $servicios = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Inserta los usuarios con las contraseñas encriptadas
    foreach ($users as $user) {
        $encrypted_password = encrypt($user['password'], $key);
        $servicio_id = $servicios[$user['servicio']];
        
        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, servicio_id) VALUES (:username, :password, :servicio_id)");
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $encrypted_password);
        $stmt->bindParam(':servicio_id', $servicio_id);
        $stmt->execute();
    }

    echo "Usuarios creados correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
