<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'lidosererna';
$username = 'root';
$password = ''; // Remplace par ton vrai mot de passe

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération de toutes les datas
// Récupération des produits
$sql = "SELECT * FROM produits";
$result = $conn->query($sql);

// Récupération des ingredients existants
$sql = "SELECT * from list_ingredient";
$result_ingredients = $conn->query($sql);

// Récupération des ingredients d'un plat et de leur type
$sql = "SELECT * from produitingredient";
$result_ingredients_plat = $conn->query($sql);

// Gestion de la soumission du formulaire pour modifier un ingredients 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier_ingredient'])) {
    $id = $_POST['id'];
    $ingredient = $_POST['ingredient'];

    // Mise à jour de l'ingrédient dans la base de données
    $sql = "UPDATE list_ingredient SET Name='$ingredient' WHERE ID='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>Ingrédient modifié avec succès.</div>";
    } else {
        echo "<div class='error'>Erreur lors de la modification de l'ingrédient: " . $conn->error . "</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_ingredient'])) {
    $ingredient = $_POST['ingredient'];
    
    if (!empty($ingredient)) {
        // Vérifier si l'ingrédient existe déjà
        $stmt = $conn->prepare("SELECT ID FROM list_ingredient WHERE Name = ?");
        $stmt->bind_param("s", $ingredient);
        $stmt->execute();
        $stmt->store_result();

        
        if ($stmt->num_rows > 0) {
            echo "<p class='error'>Un ingredient existe : \"$ingredient\" existe déjà.</p>";
            $stmt->close();
        } else {
            $stmt->close();
            $sql = "INSERT INTO list_ingredient (Name) VALUES ('$ingredient')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='success'>Ingrédient ajouté avec succès.</div>";
            } else {
                echo "<div class='error'>Erreur lors de l'ajout de l'ingrédient: " . $conn->error . "</div>";
            }
        }
    } else {
        echo "<p class='error'>Tous les champs doivent être remplis.</p>";
    }
}
// Gestion pour supprimer un ingredient
if (isset($_GET['supprimer_ingredient'])) {
    $id = $_GET['supprimer_ingredient'];
    $sql = "DELETE FROM list_ingredient WHERE ID='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>Ingrédient supprimé avec succès.</div>";
    } else {
        echo "<div class='error'>Erreur lors de la suppression de l'ingrédient: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Menus et Plats</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: gray;
            color: white;
        }
        table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: gray;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
        .success {
            color: green;
            text-align: center;
            font-weight: bold;
        }
        .delete-icon {
            color: gray;
            font-size: 20px;
            cursor: pointer;
            text-decoration: none;
        }
        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ff9800;
        }

    </style>
</head>
<body>
    <nav>
        <a href="index.php">Gestion</a>
        <a href="..\graph/graphique.php">Graphique</a>
    </nav>
    <h1>Liste des Plats Italiens</h1>
    <table>
        <thead>
            <tr>
                <th>Nom du Plat</th>
                <th>Prix (€)</th>
                <th>Description</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <form action='' method='POST'>
                                <td><input type='text' name='plat' value='" . htmlspecialchars($row['nom']) . "' required></td>
                                <td><input type='number' step='0.01' name='prix' value='" . htmlspecialchars($row['prix']) . "' required></td>
                                <td><textarea name='description' required>" . htmlspecialchars($row['description']) . "</textarea></td>
                                <td>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                    <input type='submit' name='modifier' value='Modifier'>
                                </td>
                            </form>
                            <td>
                                <a href='?supprimer=" . $row['id'] . "' class='delete-icon' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce plat ?\");'>&#128465;</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucun plat trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <!-- Table avec tous les ingredients -->
    <h1>Liste des Ingrédients</h1>
    <table>
        <thead>
            <tr>
                <th>Nom de l'Ingrédient</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_ingredients && $result_ingredients->num_rows > 0) {
                while($row = $result_ingredients->fetch_assoc()) {
                    echo "<tr>
                            <form action='' method='POST'>
                                <td><input type='text' name='ingredient' value='" . htmlspecialchars($row['Name']) . "' required></td>
                                <td>
                                    <input type='hidden' name='id' value='" . $row['ID'] . "'>
                                    <input type='submit' name='modifier_ingredient' value='Modifier'>
                                </td>
                            </form>
                            <td>
                                <a href='?supprimer_ingredient=" . $row['ID'] . "' class='delete-icon' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet ingrédient ?\");'>&#128465;</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Aucun ingrédient trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <!-- Ajouter un ingredients -->
    <h1>Ajouter un Ingrédient</h1>
    <form action="" method="POST">
        <input type="text" name="ingredient" placeholder="Nom de l'Ingrédient" required>
        <input type="submit" name="ajouter_ingredient" value="Ajouter Ingrédient">
    </form>
    <!-- Ajouter un ingredients a un produit -->
    <h1>Ajouter un Ingrédient à un Plat</h1>
    <form action="" method="POST">
        <select name="plat" required>
            <option value="">Sélectionner un Plat</option>
            <?php
            $result->data_seek(0); // Reset le pointeur de résultat
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . "</option>";
            }
            ?>
        </select>
        <select name="ingredient" required>
            <option value="">Sélectionner un Ingrédient</option>
            <?php
            $result_ingredients->data_seek(0); // Reset le pointeur de résultat
            while($row = $result_ingredients->fetch_assoc()) {
                echo "<option value='" . $row['ID'] . "'>" . htmlspecialchars($row['Name']) . "</option>";
            }
            ?>
        </select>

        <input type="number" step="1" name="quantite" placeholder="Quantité" required><br><br>

        <select name="ingredient" required>
            <option value="">Sélectionner un type (L, cl, g Kg)</option>
            <?php
            // affiche moi le retour de $result_ingredients_plat
            var_dump($result_ingredients_plat);
            $result_ingredients_plat->data_seek(0); // Reset le pointeur de résultat
            while($row = $result_ingredients_plat->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['Type']) . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="ajouter_ingredient_plat" value="Ajouter Ingrédient au Plat">
    </form>