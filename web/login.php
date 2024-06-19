<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="general.css">
</head>
<body>
<header>
    <img src="imagenes\logo.jpeg" alt="Logo de G&C Bank" class="logo">
    <h1>G&C Bank</h1>
  </header>
    <h2>Login</h2>
    <form action="authenticate.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit" value="Login">Login</button>
       
    </form>
</body>
</html>
