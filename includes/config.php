<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "tu_usuario_mysql";
$password = "tu_contraseña_mysql";
$dbname = "bdimc";

// Crear conexión
function conectarBD()
{
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}

// Función para registrar usuario
function registrarUsuario($cedula)
{
    $conn = conectarBD();

    // Preparar consulta para insertar usuario
    $stmt = $conn->prepare("INSERT INTO tusuario (ccedula) VALUES (?) ON DUPLICATE KEY UPDATE ccedula = ccedula");
    $stmt->bind_param("s", $cedula);

    if ($stmt->execute()) {
        // Obtener ID del usuario
        $usuarioID = $stmt->insert_id > 0 ? $stmt->insert_id : $conn->query("SELECT nusuarioID FROM tusuario WHERE ccedula = '$cedula'")->fetch_assoc()['nusuarioID'];

        $stmt->close();
        $conn->close();
        return $usuarioID;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Función para guardar registro de IMC
function guardarIMC($usuarioID, $altura, $peso, $imc, $categoria)
{
    $conn = conectarBD();

    // Preparar consulta para insertar IMC
    $stmt = $conn->prepare("INSERT INTO timc (naltura, npeso, nimc, ccategoria) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $altura, $peso, $imc, $categoria);

    if ($stmt->execute()) {
        $imcID = $conn->insert_id;

        // Insertar relación usuario-IMC
        $stmt2 = $conn->prepare("INSERT INTO timc_usuario (nusuarioFK, nimcFK) VALUES (?, ?)");
        $stmt2->bind_param("ii", $usuarioID, $imcID);
        $stmt2->execute();

        $stmt->close();
        $stmt2->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Procesar formulario de envío
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cc'];
    $altura = $_POST['altura'];
    $peso = $_POST['peso'];
    $imc = $_POST['imc'];
    $categoria = $_POST['categoria'];

    // Registrar usuario y obtener su ID
    $usuarioID = registrarUsuario($cedula);

    if ($usuarioID) {
        // Guardar registro de IMC
        if (guardarIMC($usuarioID, $altura, $peso, $imc, $categoria)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Registro guardado exitosamente'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al guardar el registro de IMC'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar el usuario'
        ]);
    }
}
