<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Obtener los datos del formulario
    $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
    $altura = isset($_POST['altura']) ? floatval($_POST['altura']) : 0;
    $peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $imc = isset($_POST['imc']) ? floatval($_POST['imc']) : 0;
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';

    if (empty($cedula) || empty($altura) || empty($peso) || empty($imc) || empty($categoria)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    // Preparar la llamada al procedimiento almacenado
    $stmt = $conn->prepare("CALL sp_insertar_imc(?, ?, ?, ?, ?)");
    $stmt->bind_param("sddds", $cedula, $altura, $peso, $imc, $categoria);

    // Ejecutar el procedimiento almacenado
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['result'] === 'SUCCESS') {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}