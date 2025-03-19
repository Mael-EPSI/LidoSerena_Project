<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$pdo = require 'db.php';
require_once 'NotificationSystem.php';

// On suppose que tu veux TOUTES les notifications de type "serveur" et statut "non_lu"
$notifSystem = new NotificationSystem($pdo);
$notifications = $notifSystem->getNotifications('serveur', 'non_lu');

echo json_encode(["success" => true, "notifications" => $notifications], JSON_PRETTY_PRINT);

?>

