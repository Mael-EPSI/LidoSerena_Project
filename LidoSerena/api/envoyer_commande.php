<?php
error_reporting(0); // Désactiver l'affichage des erreurs PHP
header('Content-Type: application/json');

$pdo = require 'db.php';

// Récupérer les données JSON
$data = json_decode(file_get_contents("php://input"), true);

// Vérification des données
if (!$data || !isset($data['table_id']) || !isset($data['produits']) || !isset($data['menus'])) {
    die(json_encode([
        "success" => false, 
        "message" => "Données manquantes ou invalides"
    ]));
}

try {
    // Calcul du prix total de la commande
    $prix_total = 0;
    
    // Gestion de la commande
    $commande_id = isset($data['commande_id']) ? $data['commande_id'] : null;

    // Nouvelle commande ou vérification existante
    if (!$commande_id) {
        $stmt = $pdo->prepare("INSERT INTO commandes (table_id, date_commande, statut, prix_total) VALUES (?, NOW(), 'en cours', 0)");
        $stmt->execute([$data['table_id']]);
        $commande_id = $pdo->lastInsertId();
    }

    // Traitement des produits avec leurs prix
    foreach ($data['produits'] as $produit) {
        $stmt = $pdo->prepare("INSERT INTO commandes_details (commande_id, produit_id, prix_unitaire) VALUES (?, ?, ?)");
        $stmt->execute([$commande_id, $produit['id'], $produit['prix']]);
        $prix_total += $produit['prix'];
    }

    // Traitement des menus avec leurs prix
    foreach ($data['menus'] as $menu) {
        $stmt = $pdo->prepare("INSERT INTO commandes_menus (commande_id, menu_id, prix) VALUES (?, ?, ?)");
        $stmt->execute([$commande_id, $menu['id'], $menu['prix']]);
        $prix_total += $menu['prix'];

        // Ajout des produits du menu
        if (!empty($menu['produits'])) {
            $stmt = $pdo->prepare("INSERT INTO commandes_menus_details (commande_id, menu_id, produit_id) VALUES (?, ?, ?)");
            foreach ($menu['produits'] as $produit_id) {
                $stmt->execute([$commande_id, $menu['id'], $produit_id]);
            }
        }
    }

    // Mise à jour du prix total de la commande
    $stmt = $pdo->prepare("UPDATE commandes SET prix_total = prix_total + ? WHERE id = ?");
    $stmt->execute([$prix_total, $commande_id]);

    die(json_encode([
        "success" => true,
        "message" => "Commande enregistrée",
        "commande_id" => $commande_id,
        "prix_total" => $prix_total
    ]));

} catch (Exception $e) {
    die(json_encode([
        "success" => false,
        "message" => "Erreur serveur: " . $e->getMessage()
    ]));
}
?>
