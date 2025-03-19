<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$pdo = require 'db.php';

try {
    // Requête principale pour récupérer les commandes
    $sql = "SELECT 
                c.id as commande_id,
                c.table_id,
                c.statut
            FROM commandes c
            WHERE c.statut IN ('en cours', 'pret')
            ORDER BY c.date_commande DESC";

    $stmt = $pdo->query($sql);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($commandes as $commande) {
        $commande_id = $commande['commande_id'];

        // Récupérer les produits individuels
        $sql_produits = "SELECT 
                            p.nom,
                            cd.prix_unitaire as prix
                        FROM commandes_details cd 
                        JOIN produits p ON cd.produit_id = p.id 
                        WHERE cd.commande_id = ?";
        $stmt_produits = $pdo->prepare($sql_produits);
        $stmt_produits->execute([$commande_id]);
        $produits = $stmt_produits->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les menus et leurs produits
        $sql_menus = "SELECT 
                        m.id,
                        m.nom,
                        cm.prix,
                        GROUP_CONCAT(p.nom) as produits_noms
                    FROM commandes_menus cm
                    JOIN menus m ON cm.menu_id = m.id
                    LEFT JOIN commandes_menus_details cmd ON cm.commande_id = cmd.commande_id AND cm.menu_id = cmd.menu_id
                    LEFT JOIN produits p ON cmd.produit_id = p.id
                    WHERE cm.commande_id = ?
                    GROUP BY m.id, cm.id";
        $stmt_menus = $pdo->prepare($sql_menus);
        $stmt_menus->execute([$commande_id]);
        $menus = $stmt_menus->fetchAll(PDO::FETCH_ASSOC);

        // Structurer les menus avec leurs produits
        $menus_formated = array_map(function($menu) {
            return [
                'id' => $menu['id'],
                'nom' => $menu['nom'],
                'prix' => floatval($menu['prix']),
                'produits' => $menu['produits_noms'] ? explode(',', $menu['produits_noms']) : []
            ];
        }, $menus);

        // Ajouter à la liste des commandes
        $result[] = [
            'commande_id' => (int)$commande_id,
            'table_id' => (int)$commande['table_id'],
            'statut' => $commande['statut'],
            'produits' => $produits,
            'menus' => $menus_formated
        ];
    }

    echo json_encode([
        "success" => true,
        "commandes" => $result
    ], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur: " . $e->getMessage()
    ]);
}
?>

