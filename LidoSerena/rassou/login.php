<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername  = "digitalprojects.fr";
$db_username = "ceda8720"; // Identifiant BDD
$db_password = "c4Gt-KRWC-UCP(";
$dbname      = "ceda8720_pizza";

// Connexion à la base de données
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que les champs ne sont pas vides
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $usernameInput = trim($_POST['username']);
        $passwordInput = $_POST['password'];

        // Requête préparée pour récupérer l'utilisateur
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        if ($stmt === false) {
            die("Erreur de préparation : " . $conn->error);
        }
        $stmt->bind_param("s", $usernameInput);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $username, $hashed_password);
            $stmt->fetch();
            // Vérifier le mot de passe
            if (password_verify($passwordInput, $hashed_password)) {
                // Authentification réussie : enregistrer l'utilisateur en session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                // Rediriger vers la partie admin
                header("Location: admin/index.php");
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Utilisateur non trouvé.";
        }
        $stmt->close();
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: gray;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #333;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Connexion Admin</h1>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
