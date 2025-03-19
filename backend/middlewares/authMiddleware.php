<?php
/*require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Assure-toi d'avoir Firebase JWT installé

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function isAuthenticated() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        return null;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    return verifyJWT($token);
}

function verifyJWT($token) {
    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
        return [
            "id" => $decoded->user_id,
            "is_admin" => $decoded->role === 'admin' ? 1 : 0 // Vérifie si l'utilisateur est admin
        ];

    } catch (Exception $e) {
        return null; // Token invalide ou expiré
    }
}
?>
