<?php
require 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("
        SELECT m.id, m.nom, m.prix, 
               GROUP_CONCAT(mc.categorie_id) AS categories
        FROM menus m
        LEFT JOIN menu_categories mc ON m.id = mc.menu_id
        GROUP BY m.id
    ");
    
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir les catégories en tableau JSON
    foreach ($menus as &$menu) {
        $menu["categories"] = $menu["categories"] ? array_map('intval', explode(',', $menu["categories"])) : [];
    }

    echo json_encode($menus);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO menus (nom, prix) VALUES (?, ?)");
    $stmt->execute([$data['nom'], $data['prix']]);
    echo json_encode(["message" => "Menu ajouté"]);
}
?>
