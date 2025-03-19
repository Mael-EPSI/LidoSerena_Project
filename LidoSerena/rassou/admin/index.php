<?php

$host = 'localhost';
$dbname = 'lidoserena';
$username = 'root';
$password = ''; // Remplace par ton vrai mot de passe

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SUPPRESSION D'UN PRODUIT
if (isset($_GET['supprimer'])) {
    $idPlat = intval($_GET['supprimer']);
    $sql = "DELETE FROM produits WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPlat);
    if ($stmt->execute()) {
        echo "<p class='success'>Plat supprimé avec succès!</p>";
    } else {
        echo "<p class='error'>Erreur lors de la suppression du plat.</p>";
    }
    $stmt->close();
}

// MODIFICATION D'UN PRODUIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $idPlat = intval($_POST['id']);
    $platModifie = htmlspecialchars($_POST['plat']);
    $prix = floatval($_POST['prix']);
    $description = htmlspecialchars($_POST['description']);
    
    if (!empty($platModifie) && !empty($prix) && !empty($description)) {
        $sql = "UPDATE produits SET nom = ?, prix = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $platModifie, $prix, $description, $idPlat);
        if ($stmt->execute()) {
            echo "<p class='success'>Plat modifié avec succès!</p>";
        } else {
            echo "<p class='error'>Erreur lors de la modification du plat.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Tous les champs doivent être remplis.</p>";
    }
}

// AJOUT D'UN PRODUIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $plat = htmlspecialchars($_POST['plat']);
    $prix = floatval($_POST['prix']);
    $description = htmlspecialchars($_POST['description']);
    $categorie_id = intval($_POST['categorie_id']); // Ajout de la catégorie
    
    if (!empty($plat) && !empty($prix) && !empty($description) && $categorie_id > 0) {
        $sql = "INSERT INTO produits (nom, prix, description, categorie_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $plat, $prix, $description, $categorie_id);
        if ($stmt->execute()) {
            echo "<p class='success'>Plat ajouté avec succès!</p>";
        } else {
            echo "<p class='error'>Erreur lors de l'ajout du plat.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error'>Tous les champs doivent être remplis et une catégorie doit être sélectionnée.</p>";
    }
}

// SUPPRESSION D'UN MENU
if (isset($_GET['supprimer_menu'])) {
    $idMenu = intval($_GET['supprimer_menu']);
    // Supprimer d'abord les associations dans la table menu_categories
    $stmt = $conn->prepare("DELETE FROM menu_categories WHERE menu_id = ?");
    $stmt->bind_param("i", $idMenu);
    $stmt->execute();
    $stmt->close();
    
    $sql = "DELETE FROM menus WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idMenu);
    if ($stmt->execute()) {
        echo "<p class='success'>Menu supprimé avec succès!</p>";
    } else {
        echo "<p class='error'>Erreur lors de la suppression du menu.</p>";
    }
    $stmt->close();
}

// AJOUT D'UN MENU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_menu'])) {
    $nomMenu = htmlspecialchars($_POST['nom_menu']);
    $abreviation = htmlspecialchars($_POST['abreviation']);
    $prixMenu = floatval($_POST['prix_menu']);
    
    if (!empty($nomMenu) && !empty($abreviation) && !empty($prixMenu)) {
        // Vérifier si un menu portant ce nom existe déjà
        $stmt = $conn->prepare("SELECT id FROM menus WHERE nom = ?");
        $stmt->bind_param("s", $nomMenu);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "<p class='error'>Un menu portant le nom \"$nomMenu\" existe déjà.</p>";
            $stmt->close();
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO menus (nom, abreviation, prix) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $nomMenu, $abreviation, $prixMenu);
            if ($stmt->execute()) {
                echo "<p class='success'>Menu ajouté avec succès!</p>";
            } else {
                echo "<p class='error'>Erreur lors de l'ajout du menu.</p>";
            }
            $stmt->close();
        }
    } else {
        echo "<p class='error'>Tous les champs doivent être remplis.</p>";
    }
}

// AJOUT D'UNE CATÉGORIE DANS UN MENU (sans gestion de prix)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_categorie_menu'])) {
    $menuId = intval($_POST['menu_id']);
    $categorieId = intval($_POST['categorie_id']);
    
    $stmt = $conn->prepare("INSERT INTO menu_categories (menu_id, categorie_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $menuId, $categorieId);
    if ($stmt->execute()) {
        echo "<p class='success'>Catégorie ajoutée au menu avec succès.</p>";
    } else {
        echo "<p class='error'>Erreur lors de l'ajout de la catégorie au menu.</p>";
    }
    $stmt->close();
}

// SUPPRESSION D'UNE CATÉGORIE DANS UN MENU
if (isset($_GET['supprimer_categorie_menu'])) {
    $idMC = intval($_GET['supprimer_categorie_menu']); // identifiant dans la table menu_categories

    $stmt = $conn->prepare("DELETE FROM menu_categories WHERE id = ?");
    $stmt->bind_param("i", $idMC);
    if ($stmt->execute()) {
        echo "<p class='success'>Catégorie supprimée du menu.</p>";
    } else {
        echo "<p class='error'>Erreur lors de la suppression de la catégorie du menu.</p>";
    }
    $stmt->close();
}

// Récupération des produits
$sql = "SELECT * FROM produits";
$result = $conn->query($sql);

// Récupération des menus avec leurs informations
$sqlMenus = "SELECT * FROM menus ORDER BY nom ASC";
$resultMenus = $conn->query($sqlMenus);

// Avant le formulaire d'ajout, ajouter la récupération des catégories
$sqlCategories = "SELECT * FROM categories ORDER BY nom";
$resultCategories = $conn->query($sqlCategories);
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

    <h2 style="text-align: center;">Ajouter un Nouveau Plat</h2>
    <form action="" method="POST" style="width: 80%; margin: 0 auto;">
        <input type="text" name="plat" placeholder="Nom du plat" required><br><br>
        <input type="number" step="0.01" name="prix" placeholder="Prix" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <select name="categorie_id" required>
            <option value="">Sélectionner une catégorie</option>
            <?php
            if ($resultCategories && $resultCategories->num_rows > 0) {
                while($cat = $resultCategories->fetch_assoc()) {
                    echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['nom']) . "</option>";
                }
            }
            ?>
        </select><br><br>
        <input type="submit" name="ajouter" value="Ajouter">
    </form>

    <h1>Liste des Menus</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Menu</th>
                <th>Abréviation</th>
                <th>Prix (€)</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $menusArray = [];
            if ($resultMenus && $resultMenus->num_rows > 0) {
                while ($menu = $resultMenus->fetch_assoc()) {
                    $menusArray[] = $menu;
                    echo "<tr>
                            <td>" . htmlspecialchars($menu['id']) . "</td>
                            <td>" . htmlspecialchars($menu['nom']) . "</td>
                            <td>" . htmlspecialchars($menu['abreviation']) . "</td>
                            <td>" . htmlspecialchars($menu['prix']) . " €</td>
                            <td>
                                <a href='?supprimer_menu=" . $menu['id'] . "' class='delete-icon' onclick='return confirm(\"Supprimer ce menu ?\");'>&#128465;</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun menu disponible.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2 style="text-align: center;">Ajouter un Nouveau Menu</h2>
    <form action="" method="POST" style="width: 80%; margin: 0 auto;">
        <input type="text" name="nom_menu" placeholder="Nom du menu" required><br><br>
        <input type="text" name="abreviation" placeholder="Abréviation" required><br><br>
        <input type="number" step="0.01" name="prix_menu" placeholder="Prix du menu" required><br><br>
        <input type="submit" name="ajouter_menu" value="Ajouter">
    </form>

    <h2 style="text-align: center;">Associer des Catégories à un Menu</h2>
    <?php 
    // Récupération de toutes les catégories
    $resultCategories = $conn->query("SELECT * FROM categories ORDER BY nom ASC");
    $allCategories = ($resultCategories && $resultCategories->num_rows > 0) ? $resultCategories->fetch_all(MYSQLI_ASSOC) : [];
    ?>
    <?php if (!empty($menusArray)): ?>
        <?php foreach ($menusArray as $menu): ?>
            <h3>Menu : <?= htmlspecialchars($menu['nom']) ?> (ID <?= $menu['id'] ?>)</h3>
            <?php
            $menuId = intval($menu['id']);
            $sqlMC = "SELECT mc.id AS mc_id, c.nom AS cat_nom
                      FROM menu_categories mc
                      LEFT JOIN categories c ON mc.categorie_id = c.id
                      WHERE mc.menu_id = $menuId
                      ORDER BY c.nom ASC";
            $resMC = $conn->query($sqlMC);
            ?>
            <table>
                <tr>
                    <th>Catégorie</th>
                    <th>Retirer</th>
                </tr>
                <?php if ($resMC && $resMC->num_rows > 0): ?>
                    <?php while($assoc = $resMC->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($assoc['cat_nom']) ?></td>
                            <td>
                                <a href='?supprimer_categorie_menu=<?= $assoc['mc_id'] ?>' class='delete-icon' onclick='return confirm("Retirer cette catégorie du menu ?");'>&#128465;</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2">Aucune catégorie associée à ce menu.</td></tr>
                <?php endif; ?>
            </table>
            <!-- Formulaire pour associer une nouvelle catégorie au menu -->
            <?php if (!empty($allCategories)): ?>
                <form method="post" action="">
                    <input type="hidden" name="menu_id" value="<?= $menuId ?>">
                    <label for="categorie_id_<?= $menuId ?>">Catégorie :</label>
                    <select name="categorie_id" id="categorie_id_<?= $menuId ?>">
                        <?php foreach ($allCategories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" name="ajouter_categorie_menu" value="Ajouter au menu">
                </form>
            <?php else: ?>
                <p>Aucune catégorie à associer.</p>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun menu disponible pour associer des catégories.</p>
    <?php endif; ?>

</body>
</html>
<?php
$conn->close();
?>
