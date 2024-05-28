<?php
require '../config.php';
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

function verifyJWT() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, JWT_SECRET, ['HS256']);
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
    return null;
}
