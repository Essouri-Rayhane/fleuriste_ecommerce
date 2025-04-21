<?php
// ==============================================
// CONFIGURATION ET INITIALISATION
// ==============================================

// Démarrer la session
session_start();

// Vérifier l'authentification de l'admin

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'fleuriste_ecommerce');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

// Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", 
        DB_USER, 
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

/**
 * Fonction pour exécuter des requêtes SQL sécurisées
 * 
 * @param PDO $pdo Instance PDO
 * @param string $query Requête SQL
 * @param array $params Paramètres
 * @return array Résultats
 */
function fetchData(PDO $pdo, string $query, array $params = []): array {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ==============================================
// RÉCUPÉRATION DES DONNÉES
// ==============================================

// 1. Statistiques principales
$stats = fetchData($pdo, "
    SELECT 
        (SELECT SUM(p.prix * cp.quantite) FROM commande_produit cp JOIN produit p ON cp.id_produit = p.id_produit) AS total_ventes,
        (SELECT COUNT(*) FROM commande WHERE DATE(date_commande) = CURDATE()) AS commandes_aujourdhui,
        (SELECT COUNT(DISTINCT id_client) FROM commande) AS clients_uniques,
        (SELECT AVG(p.prix * cp.quantite) FROM commande_produit cp JOIN produit p ON cp.id_produit = p.id_produit) AS panier_moyen
")[0] ?? [];

// 2. Produits les plus vendus
$produitsTops = fetchData($pdo, "
    SELECT p.id_produit, p.nom, p.image, SUM(cp.quantite) AS total_vendus 
    FROM commande_produit cp
    JOIN produit p ON cp.id_produit = p.id_produit
    GROUP BY p.id_produit
    ORDER BY total_vendus DESC
    LIMIT 5
");

// 3. Commandes par jour (30 derniers jours)
$commandesParJour = fetchData($pdo, "
    SELECT DATE(date_commande) AS jour, COUNT(*) AS nb_commandes 
    FROM commande 
    WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY jour 
    ORDER BY jour
");

// 4. Catégories populaires
$categoriesPopulaires = fetchData($pdo, "
    SELECT c.nom_categorie, COUNT(cp.id_produit) AS total_vendus
    FROM commande_produit cp
    JOIN produit p ON cp.id_produit = p.id_produit
    JOIN categorie c ON p.id_categorie = c.id_categorie
    GROUP BY c.id_categorie
    ORDER BY total_vendus DESC
    LIMIT 5
");

// 5. Commandes récentes
$recentOrders = fetchData($pdo, "
    SELECT c.id_commande, cl.nom, cl.prenom, c.date_commande, 
           SUM(p.prix * cp.quantite) AS montant, c.statut
    FROM commande c
    JOIN client cl ON c.id_client = cl.id_client
    JOIN commande_produit cp ON c.id_commande = cp.id_commande
    JOIN produit p ON cp.id_produit = p.id_produit
    GROUP BY c.id_commande
    ORDER BY c.date_commande DESC
    LIMIT 5
");

// Préparation des données pour les graphiques
$chartData = [
    'labels' => array_column($commandesParJour, 'jour'),
    'values' => array_column($commandesParJour, 'nb_commandes'),
    'categories' => array_column($categoriesPopulaires, 'nom_categorie'),
    'catValues' => array_column($categoriesPopulaires, 'total_vendus')
];

// ==============================================
// AFFICHAGE HTML
// ==============================================
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord | fafa Fleuriste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        :root {
            --primary: #6d9773;
            --primary-dark: #3a5a40;
            --secondary: #d9e4d1;
            --light: #f6f2eb;
            --dark: #2d2d2d;
            --success: #4caf50;
            --info: #2196f3;
            --warning: #ff9800;
            --danger: #f44336;
            --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            --radius: 16px;
            --transition: 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        /* Layout */
        .app-container {/* ========== LAYOUT ========== */

  display: flex;
  width: 100%;
  max-width: 1400px;
  min-height: 90vh;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(12px);
  animation: fadeInUp 1s ease;

        }

        /* Sidebar */
         /* Variables de couleur */
    :root {
      --Rosepoudré: #f6f2eb;
      --Rosedragée: #d9e4d1;
      --Roseframboise: #6d9773;
      --Rosefuchsia: #3a5a40;
    

   /* Autres */
  --text-dark: #2d2d2d;
  --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  --radius: 16px;
  --transition: 0.3s ease;
  --h2-size: clamp(2rem, 5vw, 3rem);
}

/* ========== RESET ========== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html {
  scroll-behavior: smooth;
}

body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, var(--rose-light), var(--Rosedragée), var(--Roseframboise));
  background-size: 400% 400%;
  animation: gradientFlow 10s ease infinite;
  min-height: 100vh;
  overflow-x: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

/* ========== ANIMATIONS ========== */
@keyframes gradientFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInLeft {
  from { transform: translateX(-100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

      /* -------------------------------------------------------------------------------------------------------------------------------------------- */
      .sidebar {
  width: 250px;
  background-color: var(--rose-dark);
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  animation: slideInLeft 0.8s ease;
  background: url('../../images/sidebar.jpg') no-repeat;
  background-size: cover; /* ou contain, selon l'effet souhaité */
  background-position: center center; /* centre l'image */
  background-attachment: local; /* évite le défilement fixe */

}

.sidebar .logo {
  font-size: 1.8em;
  font-weight: bold;
  text-align: center;
  color: var(--Rosepoudré);
  margin-bottom: 40px;
}
.sidebar .logo span{
  font-size: 1.8em;
  font-weight: bold;
  text-align: center;
  color: var(--Rosepoudré);
  margin-bottom: 40px;
}

.sidebar .menu ul {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.sidebar .menu a {
  color: white;
  text-decoration: none;
  padding: 10px 15px;
  border-radius: 12px;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 12px;
}

.sidebar .menu a:hover,
.sidebar .menu a.active {
  background-color: var(--Roseframboise);
  transform: translateX(5px);
}

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h1 {
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Boutons */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        /* Cartes de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .change {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .change.up { color: var(--success); }
        .change.down { color: var(--danger); }

        /* Graphiques */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 992px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .chart-card h2 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Produits */
        .top-products {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .product-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .product-item:hover {
            background: var(--secondary);
        }

        .product-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .product-sales {
            font-size: 0.9rem;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-primary {
            background: var(--primary);
            color: white;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: var(--light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        tr:hover {
            background: var(--secondary);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-livree { background: #d4edda; color: #155724; }
        .status-en-cours { background: #cce5ff; color: #004085; }
        .status-en-attente { background: #fff3cd; color: #856404; }

        .action-btn {
            color: var(--info);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .card-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .sidebar .menu ul {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }

            .main-content {
                padding: 20px;
            }

            header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Barre latérale -->
        <aside class="sidebar">
            <div class="logo">
                <i class="fas fa-store"></i><br>
                <span>Admin Panel</span>
            </div>
            
            <nav class="menu">
                <ul>
                    <li><a href="../../index.php"><i class="fa fa-home"></i> Accueil</a></li>
                    <li><a href="ajouter_produit.php"><i class="fa fa-plus"></i> Ajouter Produit</a></li>
                    <li><a href="produits.php"><i class="fa fa-box"></i> Produits</a></li>
                    <li><a href="commandes.php"><i class="fa fa-shopping-cart"></i> Commandes</a></li>
                    <li><a href="expeditions.php"><i class="fa fa-truck"></i> Expéditions</a></li>
                    <li><a href="rapports.php" class="active"><i class="fa fa-chart-bar"></i> Rapports</a></li>
                    <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Contenu principal -->
        <main class="main-content">
            <header>
                <h1><i class="fas fa-chart-line"></i> Tableau de Bord</h1>
                <div class="period-selector">
                    <button class="btn btn-outline">7 jours</button>
                    <button class="btn btn-outline active">30 jours</button>
                    <button class="btn btn-outline">90 jours</button>
                    <button class="btn btn-primary"><i class="fas fa-download"></i> Exporter</button>
                </div>
            </header>

            <!-- Cartes de statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="fas fa-euro-sign"></i> Chiffre d'affaires</h3>
                    <div class="value"><?= number_format($stats['total_ventes'] ?? 0, 2, ',', ' ') ?> €</div>
                    <div class="change up">
                        <i class="fas fa-arrow-up"></i> 12% vs période précédente
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3><i class="fas fa-shopping-cart"></i> Commandes aujourd'hui</h3>
                    <div class="value"><?= $stats['commandes_aujourdhui'] ?? 0 ?></div>
                    <div class="change up">
                        <i class="fas fa-arrow-up"></i> 3% vs hier
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3><i class="fas fa-users"></i> Clients uniques</h3>
                    <div class="value"><?= $stats['clients_uniques'] ?? 0 ?></div>
                    <div class="change up">
                        <i class="fas fa-arrow-up"></i> 8% ce mois-ci
                    </div>
                </div>
                
                <div class="stat-card">
                    <h3><i class="fas fa-basket-shopping"></i> Panier moyen</h3>
                    <div class="value"><?= number_format($stats['panier_moyen'] ?? 0, 2, ',', ' ') ?> €</div>
                    <div class="change down">
                        <i class="fas fa-arrow-down"></i> 2% vs mois dernier
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="charts-container">
                <div class="chart-card">
                    <h2><i class="fas fa-chart-bar"></i> Évolution des commandes (30 derniers jours)</h2>
                    <div id="ordersChart" style="height: 300px;"></div>
                </div>
                
                <div class="chart-card">
                    <h2><i class="fas fa-chart-pie"></i> Catégories populaires</h2>
                    <div id="categoriesChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Produits les plus vendus -->
            <div class="top-products">
                <h2><i class="fas fa-star"></i> Top 5 des produits</h2>
                <div class="product-list">
                    <?php foreach ($produitsTops as $index => $prod): ?>
                    <div class="product-item">
                        <img src="../produits/images/<?= htmlspecialchars($prod['image'] ?? 'default.jpg') ?>" 
                             alt="<?= htmlspecialchars($prod['nom']) ?>" 
                             class="product-img">
                        <div class="product-info">
                            <div class="product-name"><?= htmlspecialchars($prod['nom']) ?></div>
                            <div class="product-sales"><?= $prod['total_vendus'] ?> ventes</div>
                        </div>
                        <span class="badge badge-primary">#<?= $index + 1 ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dernières commandes -->
            <div class="chart-card">
                <h2><i class="fas fa-clock"></i> Commandes récentes</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['id_commande'] ?></td>
                            <td><?= htmlspecialchars($order['prenom']) ?> <?= htmlspecialchars($order['nom']) ?></td>
                            <td><?= date('d/m/Y', strtotime($order['date_commande'])) ?></td>
                            <td><?= number_format($order['montant'], 2, ',', ' ') ?> €</td>
                            <td>
                                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $order['statut'])) ?>">
                                    <?= $order['statut'] ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-btn">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="card-footer">
                    <button class="btn btn-outline">Voir toutes les commandes</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Graphique des commandes
        const ordersChart = new ApexCharts(document.querySelector("#ordersChart"), {
            series: [{
                name: "Commandes",
                data: <?= json_encode($chartData['values']) ?>
            }],
            chart: {
                type: 'area',
                height: '100%',
                toolbar: { show: true },
                zoom: { enabled: true }
            },
            colors: ['#6d9773'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                }
            },
            xaxis: {
                categories: <?= json_encode($chartData['labels']) ?>,
                labels: { rotate: -45 }
            },
            tooltip: {
                y: { formatter: function(val) { return val + " commandes" } }
            }
        });
        ordersChart.render();

        // Graphique des catégories
        const categoriesChart = new ApexCharts(document.querySelector("#categoriesChart"), {
            series: <?= json_encode($chartData['catValues']) ?>,
            chart: {
                type: 'donut',
                height: '100%'
            },
            labels: <?= json_encode($chartData['categories']) ?>,
            colors: ['#6d9773', '#8fb996', '#b7cfb7', '#d9e4d1', '#f6f2eb'],
            legend: { position: 'bottom' },
            plotOptions: {
                pie: {
                    donut: { labels: { show: true, total: { show: true } } }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: { chart: { width: 200 } }
            }]
        });
        categoriesChart.render();
    </script>
</body>
</html>