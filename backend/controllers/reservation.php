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

try {
    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $pdo->prepare("SELECT * FROM reservations");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reservations) {
        echo json_encode($reservations); // Renvoie les réservations en format JSON
    } else {
        echo json_encode(["status" => "error", "message" => "Aucune réservation trouvée."]);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($data['user_id'], $data['date'], $data['time'])) {
        echo json_encode(["status" => "error", "message" => "Données manquantes."], JSON_THROW_ON_ERROR);
        exit();
    }

    $user_id = $data['user_id'];
    $date = $data['date'];
    $time = $data['time'];

    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE date = ? AND time = ?");
    $stmt->execute([$date, $time]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Cet horaire est déjà réservé."], JSON_THROW_ON_ERROR);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, date, time) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $date, $time])) {
        echo json_encode(["status" => "success", "message" => "Réservation enregistrée."], JSON_THROW_ON_ERROR);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur lors de la réservation."], JSON_THROW_ON_ERROR);
    }
}
//partir admin
// Vérifier si la requête est POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true, 512, JSON_THROW_ON_ERROR);

}
// Vérifier si l'action est 'approve' ou 'cancel'
if (isset($data['action'])) {
    $reservation_id = $data['reservation_id'];

    // Approbation de réservation
    if ($data['action'] === 'approve') {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'approved' WHERE id = ?");
        if ($stmt->execute([$reservation_id])) {
            echo json_encode(["status" => "success", "message" => "Réservation approuvée."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'approbation."]);
        }
        exit;
    }

    // Annulation de réservation
    if ($data['action'] === 'cancel') {
        $stmt = $pdo->prepare("UPDATE reservations SET status = 'canceled' WHERE id = ?");
        if ($stmt->execute([$reservation_id])) {
            echo json_encode(["status" => "success", "message" => "Réservation annulée."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'annulation."]);
        }
        exit;
    }
}

// Si l'action n'est ni approve ni cancel, renvoyer une erreur
echo json_encode(["status" => "error", "message" => "Action inconnue."]);


// Si l'action n'est ni approve ni cancel, renvoyer une erreur
echo json_encode(["status" => "error", "message" => "Action inconnue."], JSON_THROW_ON_ERROR);
?>
