<?php
require 'db.php';
require 'encryption.php'; // Include encryption functions
session_start();

$key = 'this_is_a_very_secure_key'; // Use the same secret key for encryption and decryption

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, servicio_id FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && decrypt($user['password'], $key) === $password) {
        $_SESSION['userid'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['servicio_id'] = $user['servicio_id'];
        header("Location: avanzar_turno.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
        
    }
}
?>
