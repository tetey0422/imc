<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

try {
    $cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';

    if (empty($cedula)) {
        throw new Exception('Cédula no proporcionada');
    }

    // Primero verificamos si el usuario existe
    $queryUsuario = "SELECT nusuarioID FROM tusuario WHERE ccedula = ?";
    $stmtUsuario = $conn->prepare($queryUsuario);
    $stmtUsuario->bind_param("s", $cedula);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    if ($resultUsuario->num_rows === 0) {
        echo json_encode([
            'status' => 'no_user',
            'message' => 'Usuario no encontrado'
        ]);
        exit;
    }

    // Si el usuario existe, buscamos sus registros
    $query = "
        SELECT t.dfecha, t.naltura, t.npeso, t.nimc, t.ccategoria
        FROM timc t
        INNER JOIN timc_usuario tu ON t.nimcID = tu.nimcFK
        INNER JOIN tusuario u ON tu.nusuarioFK = u.nusuarioID
        WHERE u.ccedula = ?
        ORDER BY t.dfecha DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'no_records',
            'message' => 'No hay registros de IMC para este usuario'
        ]);
        exit;
    }

    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = [
            'dfecha' => $row['dfecha'],
            'naltura' => $row['naltura'],
            'npeso' => $row['npeso'],
            'nimc' => $row['nimc'],
            'ccategoria' => $row['ccategoria']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $historial
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmtUsuario)) {
        $stmtUsuario->close();
    }
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}
