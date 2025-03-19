<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

// Connexion à la base de données
$pdo = require 'db.php';

try {
    // Récupérer uniquement les commandes en cours
    $query = "SELECT * FROM commandes WHERE statut = 'en cours' ORDER BY id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($commandes as $commande) {
        $commande_id = $commande['id'];
        $table_id    = $commande['table_id'];
        $statut      = $commande['statut'];

        // Récupérer les produits normaux (commandes_details)
        $produitsNormaux = [];
        $sqlNormaux = "
            SELECT cd.id AS line_id,
                   p.nom,
                   cd.statut AS produit_statut,
                   cd.produit_id
            FROM commandes_details cd
            JOIN produits p ON cd.produit_id = p.id
            WHERE cd.commande_id = ?
        ";
        $stmtNormaux = $pdo->prepare($sqlNormaux);
        $stmtNormaux->execute([$commande_id]);
        $rowsNormaux = $stmtNormaux->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowsNormaux as $row) {
            $produitsNormaux[] = [
                "line_id" => (int) $row["line_id"],
                "nom"     => $row["nom"],
                "statut"  => $row["produit_statut"] ?: "en_cours",
                "mode"    => "normal"
            ];
        }

        // Récupérer les produits issus des menus (commandes_menus_details)
        $produitsMenus = [];
        $sqlMenus = "
            SELECT cmd.id AS line_id,
                   p.nom,
                   cmd.statut AS produit_statut,
                   cmd.produit_id
            FROM commandes_menus_details cmd
            JOIN produits p ON cmd.produit_id = p.id
            WHERE cmd.commande_id = ?
        ";
        $stmtMenus = $pdo->prepare($sqlMenus);
        $stmtMenus->execute([$commande_id]);
        $rowsMenus = $stmtMenus->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowsMenus as $row) {
            $produitsMenus[] = [
                "line_id" => (int) $row["line_id"],
                "nom"     => $row["nom"],
                "statut"  => $row["produit_statut"] ?: "en_cours",
                "mode"    => "menu"
            ];
        }

        // Fusionner les produits normaux et ceux issus des menus
        $tous_les_produits = array_merge($produitsNormaux, $produitsMenus);

        // Ajouter cette commande au résultat
        $result[] = [
            "commande_id" => (int) $commande_id,
            "table_id"    => (int) $table_id,
            "statut"      => $statut,
            "produits"    => $tous_les_produits
        ];
    }

    echo json_encode(["success" => true, "commandes" => $result], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur: " . $e->getMessage()]);
}
?>
