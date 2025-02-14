<?php
// Debug : Voir ce que le frontend envoie
$data = json_decode(file_get_contents("php://input"), true);
file_put_contents("debug_log.txt", print_r($data, true)); // Stocker dans un fichier pour voir les données
// ce code ôr verifier si les données sont ocrrect

header("Access-Control-Allow-Origin: *"); // Autorise toutes les origines (à restreindre en production)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Autorise ces méthodes HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Autorise ces headers

// Gérer les requêtes OPTIONS (prévol CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
//global $pdo;
require __DIR__ . '/../config/config.php'; // Inclusion du fichier de configuration pour la connexion à la base de données
/** @var PDO $pdo */ //$pdo est bien une instance de PDO
// Inscription d'un utilisateur
// Récupération des données JSON envoyées par Angular
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action']) && $data['action'] === "register") {
        $name = $data['name'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insérer dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            echo json_encode(["status" => "success", "message" => "Utilisateur enregistré avec succès."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'inscription."]);
        }
    }
}

// Connexion d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action']) && $data['action'] === "login") {
        $email = $data['email'];
        $password = $data['password'];

        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        if ($user) {
            // Debug : Afficher le mot de passe en base et ce que l'utilisateur a entré
            file_put_contents("debug_log.txt", "Password en base: " . $user['password'] . "\n", FILE_APPEND);
            file_put_contents("debug_log.txt", "Password entré: " . $password . "\n", FILE_APPEND);
            //ce code pour verrifer le mot de passe

            if (password_verify($password, $user['password'])) {
                echo json_encode(["status" => "success", "message" => "Connexion réussie !"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Email ou mot de passe incorrect."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Utilisateur non trouvé."]);
        }
    }
}


