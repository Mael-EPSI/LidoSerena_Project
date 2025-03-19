<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $stmt = $pdo->prepare("UPDATE commandes SET statut = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$data['status'], $data['commande_id']]);
        
        // Créer notification selon le statut
        $notificationSystem = new NotificationSystem($pdo);
        $message = "Commande {$data['commande_id']} est maintenant {$data['status']}";
        $notificationSystem->createNotification($data['commande_id'], $message, $data['status']);
        
        echo json_encode(["success" => true]);
    }
} catch(Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
