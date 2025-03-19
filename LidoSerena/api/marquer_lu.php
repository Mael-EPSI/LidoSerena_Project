<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

require 'db.php';

class NotificationSystem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour marquer une notification comme lue
    public function markAsRead($notification_id) {
        // La requête SQL pour mettre à jour le statut de la notification
        $sql = "UPDATE notifications SET statut = 'lu' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $notification_id]);
    }
}

// --- Récupération des données JSON du POST ---
$data = json_decode(file_get_contents("php://input"), true);

// Vérification de la présence de notification_id dans les données reçues
if (!isset($data['notification_id'])) {
    echo json_encode(["success" => false, "message" => "notification_id manquant"]);
    exit;
}

// --- Instanciation et appel de la méthode markAsRead ---
$notifSystem = new NotificationSystem($pdo);
$result = $notifSystem->markAsRead($data['notification_id']);

if ($result) {
    echo json_encode(["success" => true, "message" => "Notification marquée comme lue"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour"]);
}
