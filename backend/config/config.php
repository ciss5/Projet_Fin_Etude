<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
// Définition des variables de connexion à la base de données
$host = "localhost";
$dbname = "salon_coiffure";  // Le nom de la base de données
$username = "root";
$password = "";
/** @var PDO $pdo */
// Tentative de connexion  via PDO
try {
    // Création d une nouvelle instance de (PHP Data Objects)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configure le mode de gestion des erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$query = $pdo->query("SHOW TABLES");
    //var_dump($query->fetchAll());
    //echo"la connexion marche";
} catch (PDOException $e) {
    // En cas d'échec de la connexion, affiche un message d'erreur et arrête l'exécution du script
    die("Erreur de connexion : " . $e->getMessage());
}
?>
