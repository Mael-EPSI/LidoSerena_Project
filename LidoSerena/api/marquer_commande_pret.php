<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$pdo = require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['commande_id'])) {
    echo json_encode(["success" => false, "message" => "commande_id manquant"]);
    exit;
}

$commande_id = $data['commande_id'];

try {
    $stmt = $pdo->prepare("UPDATE commandes SET statut = 'pret' WHERE id = ?");
    $stmt->execute([$commande_id]);
    echo json_encode(["success" => true, "message" => "Commande mise à jour en 'pret'"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
?>
