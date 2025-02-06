<?php
//global $pdo;
require __DIR__ . '/../config/config.php';
/** @var PDO $pdo */ //$pdo est bien une instance de PDO
//var_dump($pdo);
// Ajouter une réservation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reserve'])) {
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Vérifier si l'horaire est déjà pris
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE date = ? AND time = ?");
    $stmt->execute([$date, $time]);
    if ($stmt->rowCount() > 0) {
        echo "Cet horaire est déjà réservé.";
        exit;
    }

    // Ajouter la réservation
    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, date, time) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $date, $time])) {
        echo "Réservation enregistrée.";
    } else {
        echo "Erreur lors de la réservation.";
    }
}

// Obtenir la liste des réservations
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $pdo->query("SELECT reservations.*, users.name FROM reservations JOIN users ON reservations.user_id = users.id ORDER BY date, time");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservations);
}
?>
