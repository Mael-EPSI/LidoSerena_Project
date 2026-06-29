<?php
session_start();
error_reporting(0);

$pdo = require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nom) || empty($prenom) || empty($password)) {
        header("Location: ../nathan/co_clients.html?error=champs");
        exit();
    }

    $stmt = $pdo->prepare("SELECT nom, prenom, password FROM users_clients WHERE nom = ? AND prenom = ?");
    $stmt->execute([$nom, $prenom]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['nom']    = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        header("Location: ../nathan/client.html");
        exit();
    } else {
        header("Location: ../nathan/co_clients.html?error=identifiants");
        exit();
    }
}

header("Location: ../nathan/co_clients.html");
exit();
