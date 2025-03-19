<?php
require 'db.php';

class NotificationSystem {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crée une notification dans la table
    public function createNotification($commande_id, $message, $type = 'serveur') {
        $query = "INSERT INTO notifications (commande_id, message, statut, type)
                  VALUES (?, ?, 'non_lu', ?)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$commande_id, $message, $type]);
    }

    // Récupère les notifications d’un type et d’un statut
    public function getNotifications($type = 'serveur', $statut = 'non_lu') {
        $query = "SELECT n.*, c.table_id
                  FROM notifications n
                  JOIN commandes c ON n.commande_id = c.id
                  WHERE n.type = ? AND n.statut = ?
                  ORDER BY n.id DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$type, $statut]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Marque une notification comme lue
    public function markAsRead($notification_id) {
        $query = "UPDATE notifications SET statut = 'lu' WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$notification_id]);
    }
}
?>
