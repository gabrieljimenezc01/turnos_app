<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Turnos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function actualizarTurnos() {
            $.ajax({
                url: 'obtener_turnos.php',
                type: 'GET',
                success: function(data) {
                    $('#turnos').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        $(document).ready(function() {
            // Cargar turnos inicialmente
            actualizarTurnos();

            // Escuchar eventos de almacenamiento local
            window.addEventListener('storage', function(event) {
                if (event.key === 'turno_avanzado') {
                    actualizarTurnos();

                    var data = JSON.parse(event.newValue);
                    var servicio = data.servicio;
                    var siguiente_turno = data.siguiente_turno;

                    // Mostrar alerta con el siguiente turno
                    $('#alerta_turno').html(
                        `<div class="alert">
                            <strong>Próximo turno:</strong> ${servicio} - ${siguiente_turno}
                        </div>`
                    );
                }
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="general.css">
</head>
<body>
<header>
    <img src="imagenes\logo.jpeg" alt="Logo de G&C Bank" class="logo">
    <h1>G&C Bank</h1>
  </header>
    <h1>Turnos en espera</h1>
    <div id="alerta_turno"></div>
    <div id="turnos"></div>
</body>
</html>
