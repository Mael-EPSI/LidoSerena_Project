<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$pdo = require 'db.php';
require 'NotificationSystem.php';

$data = json_decode(file_get_contents("php://input"), true);

// Vérifier que les données nécessaires sont présentes
if (!isset($data['commande_id']) || !isset($data['line_id']) || !isset($data['mode'])) {
    echo json_encode([
        "success" => false,
        "message" => "Données manquantes (commande_id, line_id, mode requis)"
    ]);
    exit;
}

$commande_id = $data['commande_id'];
$line_id = $data['line_id'];
$mode = $data['mode'];

try {
    if ($mode === "normal") {
        // Met à jour le statut dans la table commandes_details
        $sql = "UPDATE commandes_details SET statut = 'pret' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$line_id]);
    } elseif ($mode === "menu") {
        // Met à jour le statut dans la table commandes_menus_details
        $sql = "UPDATE commandes_menus_details SET statut = 'pret' WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$line_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Mode invalide"]);
        exit;
    }
    // Créer une notification pour le serveur
    $notifSystem = new NotificationSystem($pdo);
    $notif_message = "Produit (ligne ID $line_id) de la commande #$commande_id est prêt";
    $notifSystem->createNotification($commande_id, $notif_message, "serveur");

    echo json_encode(["success" => true, "message" => "Produit marqué comme prêt"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
