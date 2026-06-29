<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pdo = require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom             = trim($_POST['nom'] ?? '');
    $prenom          = trim($_POST['prenom'] ?? '');
    $password        = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (empty($nom) || empty($prenom) || empty($password) || empty($passwordConfirm)) {
        header("Location: ../nathan/inscription.html?error=champs");
        exit();
    }

    if ($password !== $passwordConfirm) {
        header("Location: ../nathan/inscription.html?error=passwords");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT nom FROM users_clients WHERE nom = ? AND prenom = ?");
        $stmt->execute([$nom, $prenom]);
        if ($stmt->fetch()) {
            header("Location: ../nathan/inscription.html?error=existe");
            exit();
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users_clients (nom, prenom, password) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $hash]);

        $_SESSION['nom']    = $nom;
        $_SESSION['prenom'] = $prenom;

        header("Location: ../nathan/client.html");
        exit();
    } catch (PDOException $e) {
        die("Erreur base de données : " . $e->getMessage());
    }
}

header("Location: ../nathan/inscription.html");
exit();
