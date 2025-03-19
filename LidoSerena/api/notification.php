<?php
require 'db.php';
require 'NotificationSystem.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// En-têtes CORS, si besoin
header_remove("Access-Control-Allow-Origin");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['commande_id']) || !isset($data['status'])) {
    echo json_encode(["success" => false, "message" => "Données manquantes (commande_id ou status)"]);
    exit;
}

$commande_id = $data['commande_id'];
$status = $data['status'];

try {
    // Mettre à jour le statut de la commande
    $stmt = $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    $stmt->execute([$status, $commande_id]);

    // Créer une notification si la commande est prête
    if ($status === 'pret') {
        $notificationSystem = new NotificationSystem($pdo);
        $notificationSystem->createNotification($commande_id, "Commande #$commande_id est prête !");
    }

    echo json_encode(["success" => true, "message" => "Statut mis à jour"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
