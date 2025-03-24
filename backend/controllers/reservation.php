<?php
// http://localhost/Mon-salon-coiffure/backend/controllers/reservation.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../mail.php';

/** @var PDO $pdo */

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $stmt = $pdo->prepare("
    SELECT reservations.*, users.name AS user_name, users.email AS user_email
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
    if (!isset($data['user_id'], $data['date'], $data['time'], $data['end_time'])) {
      echo json_encode(["status" => "error", "message" => "Données manquantes."]);
      exit();
    }

    $user_id = $data['user_id'];
    $date = $data['date'];
    $time = $data['time'];
    $endTime = $data['end_time'];

    // Vérifier si un créneau chevauche cette réservation
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE date = ? AND (time < ? AND end_time > ?)");
    $stmt->execute([$date, $endTime, $time]);

    if ($stmt->rowCount() > 0) {
      echo json_encode(["status" => "error", "message" => "Ce créneau est déjà réservé."]);
      exit();
    }

    // Insérer la réservation avec l'heure de fin
    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, date, time, end_time, status) VALUES (?, ?, ?, ?, 'pending')");
    if ($stmt->execute([$user_id, $date, $time, $endTime])) {
      echo json_encode(["status" => "success", "message" => "Réservation enregistrée avec succès."]);
    } else {
      echo json_encode(["status" => "error", "message" => "Erreur lors de la réservation."]);
    }
    exit();
  }

  if (isset($data['action'], $data['reservation_id'])) {
    $reservation_id = $data['reservation_id'];

    if ($data['action'] === 'approve') {
      $stmt = $pdo->prepare("UPDATE reservations SET status = 'approved' WHERE id = ?");
    } elseif ($data['action'] === 'cancel') {
      $stmt = $pdo->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
    } else {
      echo json_encode(["status" => "error", "message" => "Action inconnue."]);
      exit();
    }

    if ($stmt->execute([$reservation_id])) {
      $stmt = $pdo->prepare("
                SELECT users.email, reservations.date, reservations.time
                FROM reservations
                JOIN users ON reservations.user_id = users.id
                WHERE reservations.id = ?
            ");
      $stmt->execute([$reservation_id]);
      $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($reservation && isset($reservation['email'])) {
        $statusText = ($data['action'] === 'approve') ? "approuvée" : "annulée";
        $to = $reservation['email'];
        $subject = "Mise à jour de votre réservation";
        $message = "Bonjour, votre réservation pour le <strong>{$reservation['date']} à {$reservation['time']}</strong> a été <strong>$statusText</strong>.";
        sendEmail($to, $subject, $message);
      }

      $check_stmt = $pdo->prepare("SELECT status FROM reservations WHERE id = ?");
      $check_stmt->execute([$reservation_id]);
      $updated_status = $check_stmt->fetch(PDO::FETCH_ASSOC);

      echo json_encode([
          "status" => "success",
          "message" => "Action effectuée avec succès et email envoyé.",
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
