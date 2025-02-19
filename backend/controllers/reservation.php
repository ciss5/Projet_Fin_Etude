<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../config/config.php';
/** @var PDO $pdo */

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($data['user_id'], $data['date'], $data['time'])) {
        echo json_encode(["status" => "error", "message" => "Données manquantes."]);
        exit();
    }

    $user_id = $data['user_id'];
    $date = $data['date'];
    $time = $data['time'];

    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE date = ? AND time = ?");
    $stmt->execute([$date, $time]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Cet horaire est déjà réservé."]);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, date, time) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $date, $time])) {
        echo json_encode(["status" => "success", "message" => "Réservation enregistrée."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur lors de la réservation."]);
    }
}
?>
