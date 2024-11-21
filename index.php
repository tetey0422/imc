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
    <?php include 'includes/header.php'; ?>
    <main>
        <div class="cuadro">
            <h1>Calculadora IMC</h1>
            <form action="/submit">
                <label for="cc">Número de Cédula:</label>
                <input type="text" id="cc" name="cc" required>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>

</body>