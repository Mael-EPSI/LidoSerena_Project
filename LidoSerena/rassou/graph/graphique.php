<?php
// 1) Connexion à la base de données
$host = 'localhost';
$dbname = 'lidosererna';
$username = 'root';
$password = ''; // Remplace par ton vrai mot de passe

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Erreur de connexion : " . $conn->connect_error);
}

// 2) Requête : nombre de commandes par produit
$sql = "
    SELECT p.nom AS produit_nom, COUNT(cd.produit_id) AS total_commandes
    FROM commandes_details cd
    JOIN produits p ON cd.produit_id = p.id
    GROUP BY cd.produit_id
    ORDER BY total_commandes DESC
";
$result = $conn->query($sql);

$plats = [];
$commandes = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $plats[] = $row['produit_nom'];
    $commandes[] = (int)$row['total_commandes'];
  }
}

// DEBUG : Afficher les erreurs SQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Requête modifiée pour obtenir les prix des commandes
$sql_prix = "SELECT 
    DATE_FORMAT(c.date_commande, '%d/%m à %H:%i') as date,
    COALESCE(
        (SELECT SUM(cd.prix_unitaire) 
         FROM commandes_details cd 
         WHERE cd.commande_id = c.id),
        0
    ) + 
    COALESCE(
        (SELECT SUM(cm.prix) 
         FROM commandes_menus cm 
         WHERE cm.commande_id = c.id),
        0
    ) as prix_total,
    c.id,
    c.statut
FROM commandes c
WHERE c.prix_total > 0 
   OR EXISTS (SELECT 1 FROM commandes_details cd WHERE cd.commande_id = c.id)
   OR EXISTS (SELECT 1 FROM commandes_menus cm WHERE cm.commande_id = c.id)
ORDER BY c.date_commande DESC
LIMIT 6";

// Debug : Afficher la requête et ses résultats
echo "<!-- Debug de la requête -->\n";
$result_prix = $conn->query($sql_prix);
if (!$result_prix) {
    echo "<!-- Erreur SQL : " . $conn->error . " -->\n";
} else {
    echo "<!-- Nombre de résultats : " . $result_prix->num_rows . " -->\n";
}

$dates_commandes = [];
$prix_commandes = [];

while ($row = $result_prix->fetch_assoc()) {
    echo "<!-- Ligne : " . print_r($row, true) . " -->\n";
    $dates_commandes[] = $row['date'];
    $prix_commandes[] = floatval($row['prix_total']);
}

// Debug : Vérifier les données
echo "<script>
console.log('Requête SQL:', " . json_encode($sql_prix) . ");
console.log('Dates:', " . json_encode($dates_commandes) . ");
console.log('Prix:', " . json_encode($prix_commandes) . ");
</script>";

// Inverser les tableaux pour l'ordre chronologique
$dates_commandes = array_reverse($dates_commandes);
$prix_commandes = array_reverse($prix_commandes);

// Debug : Vérifier les données finales
echo "<script>
console.log('Dates finales:', " . json_encode($dates_commandes) . ");
console.log('Prix finaux:', " . json_encode($prix_commandes) . ");
</script>";

// Requête pour nombre de clients par mois (1 commande = 1 client)
$sql_clients = "SELECT MONTH(date_commande) as mois, COUNT(*) as nb_clients 
                FROM commandes 
                WHERE YEAR(date_commande) = YEAR(CURRENT_DATE)
                GROUP BY mois
                ORDER BY mois";
$result_clients = $conn->query($sql_clients);

$mois = ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun", "Jul", "Aoû", "Sep", "Oct", "Nov", "Déc"];
$nb_clients = array_fill(0, 12, 0); // Initialise tableau avec 12 zéros

while ($row = $result_clients->fetch_assoc()) {
  $nb_clients[$row['mois'] - 1] = (int)$row['nb_clients'];
}

// Calcul du chiffre d'affaires total
$sql_ca_total = "SELECT SUM(prix_total) as ca_total FROM commandes";
$result_ca = $conn->query($sql_ca_total);
$ca_total = 0;
if ($result_ca && $row_ca = $result_ca->fetch_assoc()) {
    $ca_total = floatval($row_ca['ca_total']);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Graphique Commandes</title>
  <!-- Import de Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 20px;
    }

    .chart-container {
      width: 80%;
      max-width: 600px;
      margin: 0 auto;
    }

    #dishesChart {
      height: 400px;
      /* Hauteur fixe pour mieux visualiser */
    }

    h2 {
      text-align: center;
      margin: 30px 0;
    }

    nav a {
            color: black;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        nav a:hover {
            color: #ff9800;
        }

    .ca-total {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        color: #36A2EB;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 80%;
        max-width: 600px;
        border-left: 5px solid #36A2EB;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <nav>
    <a href="..\admin/index.php">Gestion</a>
    <a href="..\graph/graphique.php">Graphique</a>
  </nav>

  <div class="ca-total">
    Chiffre d'affaires total : <?php echo number_format($ca_total, 2, ',', ' '); ?> €
  </div>

  <h2>Produits les plus commandés</h2>

  <div class="chart-container">
    <canvas id="dishesChart"></canvas>
  </div>

  <h2>Prix des 6 dernières commandes</h2>
  <div class="chart-container">
    <canvas id="priceChart"></canvas>
  </div>

  <h2>Nombre de clients par mois</h2>
  <div class="chart-container">
    <canvas id="clientsChart"></canvas>
  </div>

  <script>
    // Données PHP converties en JSON et échappées directement dans le JavaScript
    const plats = [<?php echo "'" . implode("','", array_map('addslashes', $plats)) . "'"; ?>];
    const commandes = [<?php echo implode(",", $commandes); ?>];

    // Debug
    console.log('Plats:', plats);
    console.log('Commandes:', commandes);

    // Création du graphique
    const ctx = document.getElementById('dishesChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: plats,
        datasets: [{
          data: commandes,
          backgroundColor: [
            '#FF6384', '#36A2EB', '#FFCE56',
            '#4BC0C0', '#9966FF', '#FF9F40',
            '#a1d99b', '#9ecae1', '#fdae6b'
          ],
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                return `${label}: ${value} commandes`;
              }
            }
          }
        }
      }
    });

    // Modifier le graphique des prix
    new Chart(document.getElementById('priceChart').getContext('2d'), {
        type: 'bar', // Changement du type en 'bar'
        data: {
            labels: [<?php echo "'" . implode("','", array_map('addslashes', $dates_commandes)) . "'"; ?>],
            datasets: [{
                label: 'Prix des commandes (€)',
                data: [<?php echo implode(",", $prix_commandes); ?>],
                backgroundColor: '#36A2EB', // Couleur des barres
                borderColor: '#36A2EB',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Prix: ${context.raw}€`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Prix (€)'
                    }
                }
            }
        }
    });

    // Graphique des clients
    new Chart(document.getElementById('clientsChart').getContext('2d'), {
      type: 'line',
      data: {
        labels: [<?php echo "'" . implode("','", $mois) . "'"; ?>],
        datasets: [{
          label: 'Nombre de clients',
          data: [<?php echo implode(",", $nb_clients); ?>],
          borderColor: '#FF6384',
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>
</body>

</html>