<?php
//global $pdo;
require __DIR__ . '/../config/config.php'; // Inclusion du fichier de configuration pour la connexion à la base de données
/** @var PDO $pdo */ //$pdo est bien une instance de PDO
// Inscription d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];  // Récupère le nom
    $email = $_POST['email'];  // Récupère l'email
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hashage du mot de passe

    // Prépare et exécute l'insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $password])) {
        echo "Utilisateur enregistré avec succès.";  // Confirmation de l'inscription
    } else {
        echo "Erreur lors de l'inscription.";  // Message d'erreur si l'insertion échoue
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

