<?php
$host     = 'localhost';
$dbname   = 'lidosererna';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$sql = "
    SELECT u.id, u.username, u.role,
           COUNT(c.id)                     AS nb_commandes,
           COALESCE(SUM(c.prix_total), 0)  AS ca_total
    FROM users u
    LEFT JOIN commandes c ON c.serveur_id = u.id AND c.statut = 'payé'
    WHERE u.role IN ('serveur', 'cuisinier')
    GROUP BY u.id, u.username, u.role
    ORDER BY ca_total DESC
";
$result = $conn->query($sql);

$sqlTop5 = "
    SELECT p.nom, COUNT(cd.produit_id) AS nb_fois
    FROM commandes_details cd
    JOIN produits p ON p.id = cd.produit_id
    GROUP BY cd.produit_id, p.nom
    ORDER BY nb_fois DESC
    LIMIT 5
";
$resultTop5 = $conn->query($sqlTop5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info du Personnel</title>
</head>
<body>
    <nav>
        <a href="index.php">Gestion</a>
        <a href="../graph/graphique.php">Graphique</a>
        <a href="info_personnel.php">Info du Personnel</a>
    </nav>
    <h1>Info du Personnel</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Rôle</th>
                <th>Commandes traitées</th>
                <th>CA</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= $row['nb_commandes'] ?></td>
                        <td><?= number_format($row['ca_total'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Aucun serveur trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h1>Top 5 produits commandés</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produit</th>
                <th>Fois commandé</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultTop5 && $resultTop5->num_rows > 0): ?>
                <?php $rang = 1; while ($row = $resultTop5->fetch_assoc()): ?>
                    <tr>
                        <td><?= $rang++ ?></td>
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= $row['nb_fois'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">Aucune donnée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>
