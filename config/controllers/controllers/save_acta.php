<?php
require '../middleware/auth.php';

$decoded = verifyJWT();
if (!$decoded) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Acceso no autorizado'
    ]);
    exit();
}

require '../config.php';
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $usuario_id = $decoded['userId'];

    if ($id) {
        $stmt = $pdo->prepare('UPDATE actas SET titulo = ?, fecha = ? WHERE id = ?');
        $stmt->execute([$titulo, $fecha, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO actas (titulo, fecha, usuario_id) VALUES (?, ?, ?)');
        $stmt->execute([$titulo, $fecha, $usuario_id]);
    }

    header('Location: ../index.php');
}
