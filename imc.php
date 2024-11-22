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
    <link rel="stylesheet" href="./css/imc.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1>Calculadora de IMC</h1>
        <div class="contenedor">
            <div id="historial"></div>

            <form id="imcForm">
                <fieldset>
                    <legend>Seleccione el sistema para calcular su IMC</legend>
                    <ul>
                        <li>
                            <input type="radio" id="kg" name="sistema" value="kg" checked onclick="toggleAlturaOptions()">
                            <label for="kg">Metros y Kilogramos</label>
                        </li>
                        <li>
                            <input type="radio" id="lbs" name="sistema" value="lbs" onclick="toggleAlturaOptions()">
                            <label for="lbs">Altura (pulgadas o pies) y Libras</label>
                        </li>
                    </ul>
                </fieldset>

                <ul>
                    <li>
                        <label for="altura">Indique su altura:</label>
                        <input type="number" id="altura" name="altura" step="0.01" required>
                        <span id="unidadAltura">metros</span>
                    </li>
                    <li>
                        <label for="peso">Indique su peso:</label>
                        <input type="number" id="peso" name="peso" step="0.1" required>
                        <span id="unidadPeso">kg</span>
                    </li>
                </ul>

                <div id="alturaImperial" style="display: none;">
                    <label>Unidad de altura:</label>
                    <input type="radio" id="pulgadas" name="unidadAltura" value="pulgadas" checked>
                    <label for="pulgadas">Pulgadas</label>
                    <input type="radio" id="pies" name="unidadAltura" value="pies">
                    <label for="pies">Pies</label>
                </div>

                <button type="button" onclick="calcularIMC()">Calcular IMC</button>
            </form>

            <div id="resultado" style="display: none;">
                <h3>Resultado:</h3>
                <p id="imcValue"></p>
                <p id="categoriaIMC"></p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="./js/calculadora.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>