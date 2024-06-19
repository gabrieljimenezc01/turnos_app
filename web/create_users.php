<?php
require 'db.php';
require 'encryption.php'; // Include encryption functions

$key = 'this_is_a_very_secure_key'; // Use the same secret key for encryption and decryption

// Datos de los usuarios a crear


$username= 'prueba';
$passswd= '123';
$servicio='cajero';


try {
    // Inserta los servicios si no existen
    

    // Obtener los IDs de los servicios
    $stmt = $conn->prepare("SELECT id  FROM servicios WHERE nombre = :nombreser");
    $stmt->bindParam(':nombreser', $servicio);
    $stmt->execute();
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);

    $servicio_id=$servicio['id'];

    echo"id servicio: ".$servicio_id;
    // Inserta los usuarios con las contraseñas encriptadas
    
        $encrypted_password = encrypt($passswd, $key);

        $stmt = $conn->prepare("INSERT INTO usuarios (username, password, servicio_id) VALUES (:username, :password, :servicio_id)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $encrypted_password);
        $stmt->bindParam(':servicio_id', $servicio_id);
        $stmt->execute();
    

    echo "Usuarios creados correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>