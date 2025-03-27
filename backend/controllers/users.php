<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Gérer les requêtes OPTIONS (prévol CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../config/config.php'; // Connexion à la base de données
/** @var PDO $pdo */

// Récupération des données JSON envoyées par Angular
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier si une action est définie (register ou login)
if (!isset($data['action'])) {
    echo json_encode(["status" => "error", "message" => "Aucune action spécifiée."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($data['action'] === "register") {
        // Inscription d'un utilisateur
        if (!isset($data['name'], $data['email'], $data['password'])) {
            echo json_encode(["status" => "error", "message" => "Données manquantes pour l'inscription."]);
            exit();
        }

        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            echo json_encode(["status" => "success", "message" => "Utilisateur enregistré avec succès."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'inscription."]);
        }
    } elseif ($data['action'] === "login") {
        // Connexion d'un utilisateur
        if (!isset($data['email'], $data['password'])) {
            echo json_encode(["status" => "error", "message" => "Données manquantes pour la connexion."]);
            exit();
        }

        $email = $data['email'];
        $password = $data['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Connexion réussie !",
                "user" => [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "role" => $user['role'] //role => admin
                ]
            ], JSON_THROW_ON_ERROR);
        } else {
            echo json_encode(["status" => "error", "message" => "Email ou mot de passe incorrect."], JSON_THROW_ON_ERROR);
        }
    }
}




