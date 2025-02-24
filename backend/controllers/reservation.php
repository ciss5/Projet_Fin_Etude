<?php
//http://localhost/Mon-salon-coiffure/backend/controllers/reservation.php//
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

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $stmt = $pdo->prepare("
    SELECT reservations.*, users.name AS user_name
    FROM reservations
    JOIN users ON reservations.user_id = users.id
");

    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reservations ?: ["status" => "error", "message" => "Aucune réservation trouvée."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($data['action'])) {
        // Si l'action n'existe pas, on suppose que c'est une nouvelle réservation
        if (!isset($data['user_id'], $data['date'], $data['time'])) {
            echo json_encode(["status" => "error", "message" => "Données manquantes."]);
            exit();
        }

        $user_id = $data['user_id'];
        $date = $data['date'];
        $time = $data['time'];

        // Vérifier si l'horaire est déjà réservé
        $stmt = $pdo->prepare("SELECT * FROM reservations WHERE date = ? AND time = ?");
        $stmt->execute([$date, $time]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Cet horaire est déjà réservé."]);
            exit();
        }

        // Insérer la réservation
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, date, time, status) VALUES (?, ?, ?, 'pending')");
        if ($stmt->execute([$user_id, $date, $time])) {
            echo json_encode(["status" => "success", "message" => "Réservation enregistrée."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de la réservation."]);
        }
        exit();
    }

    //Gestion des actions admin (approve/cancel)
    if (isset($data['action'], $data['reservation_id'])) {
        $reservation_id = $data['reservation_id'];

        if ($data['action'] === 'approve') {
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'approved' WHERE id = ?");
        } elseif ($data['action'] === 'cancel') {
            $stmt = $pdo->prepare("UPDATE reservations SET status = 'canceled' WHERE id = ?");
        } else {
            echo json_encode(["status" => "error", "message" => "Action inconnue."]);
            exit();
        }

        // Exécution de la requête
        if ($stmt->execute([$reservation_id])) {
            // Vérification après mise à jour
            $check_stmt = $pdo->prepare("SELECT status FROM reservations WHERE id = ?");
            $check_stmt->execute([$reservation_id]);
            $updated_status = $check_stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "status" => "success",
                "message" => "Action effectuée avec succès.",
                "updated_status" => $updated_status['status']
            ], JSON_THROW_ON_ERROR);
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'exécution de l'action."]);
        }
        exit();
    }

    echo json_encode(["status" => "error", "message" => "Paramètres manquants pour l'action."]);
}
?>
