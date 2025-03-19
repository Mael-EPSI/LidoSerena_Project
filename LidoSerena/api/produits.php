<?php
require 'db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM produits");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, categorie_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['nom'], $data['description'], $data['prix'], $data['categorie_id']]);
    echo json_encode(["message" => "Produit ajouté"]);
}
?>