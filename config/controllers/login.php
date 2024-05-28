<?php
require '../config.php';
require '../db.php';
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $payload = [
            'iss' => 'localhost',  // Emisor del token
            'iat' => time(),       // Tiempo en que el token es emitido
            'exp' => time() + 3600, // Tiempo de expiración del token (1 hora)
            'userId' => $user['id'],
            'username' => $user['username']
        ];

        $jwt = JWT::encode($payload, JWT_SECRET);

        echo json_encode([
            'status' => 'success',
            'token' => $jwt
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario o contraseña incorrectos'
        ]);
    }
}
