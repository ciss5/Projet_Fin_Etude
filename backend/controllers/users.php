<?php
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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($data['action']) && $data['action'] === "register") {
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Prépare et exécute l'insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $password])) {
        echo json_encode(["message" => "Utilisateur enregistré avec succès"]);
    } else {
        echo json_encode(["error" => "Erreur lors de l'inscription"]);
    }
}


// Connexion d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];  // Récupère l'email
    $password = $_POST['password'];  // Récupère le mot de passe

    // Recherche l'utilisateur dans la base de données par email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérifie si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        echo "Connexion réussie. Bienvenue, " . $user['name'];  // Message de bienvenue si la connexion est réussie
    } else {
        echo "Email ou mot de passe incorrect.";  // Message d'erreur si les informations sont incorrectes
    }
}

