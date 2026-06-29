<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Connexion à la base de données
$pdo = require 'db.php';

// Récupérer et décoder le JSON envoyé par le front-end
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['commande_id'])) {
    echo json_encode(["success" => false, "message" => "commande_id manquant"]);
    exit;
}

$commande_id = $data['commande_id'];
$serveur_id  = $data['serveur_id'] ?? null;

try {
    $stmt = $pdo->prepare("UPDATE commandes SET statut = 'payé', serveur_id = ? WHERE id = ?");
    $stmt->execute([$serveur_id, $commande_id]);

    echo json_encode(["success" => true, "message" => "Commande payée avec succès"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
?>
