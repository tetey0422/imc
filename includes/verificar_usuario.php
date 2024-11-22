<?php
require_once 'config.php';

header('Content-Type: application/json');

$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';

if (empty($cedula)) {
    echo json_encode(['status' => 'error', 'message' => 'La cédula es requerida']);
    exit;
}

// Validar formato de cédula (8-12 dígitos)
if (!preg_match('/^\d{8,12}$/', $cedula)) {
    echo json_encode(['status' => 'error', 'message' => 'Formato de cédula inválido']);
    exit;
}

// Verificar si el usuario existe en la base de datos
$stmt = $conn->prepare("SELECT nusuarioID FROM tusuario WHERE ccedula = ?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
}

$stmt->close();
$conn->close();
