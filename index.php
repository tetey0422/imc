<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de IMC</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="cuadro">
            <h1>Calculadora IMC</h1>
            <form id="loginForm" onsubmit="return validarUsuario(event)">
                <label for="cc">Número de Cédula:</label>
                <input type="text" id="cc" name="cc" required
                    pattern="\d{8,12}"
                    title="La cédula debe tener entre 8 y 12 dígitos"
                    placeholder="Ingrese su cédula">
                <button type="submit">Ingresar</button>
                <div id="mensajeError" class="error-mensaje"></div>
            </form>
        </div>
    </main>
    <?php include './includes/footer.php'; ?>

    <script>
        function validarUsuario(event) {
            event.preventDefault();

            const cedula = document.getElementById('cc').value.trim();
            const mensajeError = document.getElementById('mensajeError');
            const submitButton = document.querySelector('button[type="submit"]');

            // Deshabilitar el botón mientras se procesa
            submitButton.disabled = true;

            // Validar formato de cédula
            if (!/^\d{8,12}$/.test(cedula)) {
                mensajeError.textContent = 'La cédula debe tener entre 8 y 12 dígitos';
                submitButton.disabled = false;
                return false;
            }

            // Crear FormData para enviar
            const formData = new FormData();
            formData.append('cedula', cedula);

            // Realizar la petición al servidor
            fetch('./includes/verificar_usuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Guardar la cédula en sessionStorage
                        sessionStorage.setItem('cedula', cedula);
                        // Redirigir a la página de la calculadora
                        window.location.href = 'imc.php';
                    } else {
                        mensajeError.textContent = data.message || 'Usuario no encontrado';
                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mensajeError.textContent = 'Error al verificar el usuario. Por favor, intente nuevamente.';
                    submitButton.disabled = false;
                });

            return false;
        }

        // Limpiar mensaje de error cuando el usuario empiece a escribir
        document.getElementById('cc').addEventListener('input', function() {
            document.getElementById('mensajeError').textContent = '';
        });
    </script>
</body>

</html>