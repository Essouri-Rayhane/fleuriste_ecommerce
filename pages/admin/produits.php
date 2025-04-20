<?php
include('../../config/db.php');
session_start();

$message = "";

// Récupérer les produits depuis la base
try {
    $stmt = $conn->query("SELECT * FROM produit");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='message error'>❌ Erreur de chargement des produits : " . $e->getMessage() . "</div>";
}

// Action pour supprimer un produit
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
    $id_produit = $_GET['delete'];
    try {
        $stmt = $conn->prepare("DELETE FROM produit WHERE id_produit = ?");
        if ($stmt->execute([$id_produit])) {
            $message = "<div class='message success'>✅ Produit supprimé avec succès !</div>";
        } else {
            $message = "<div class='message error'>❌ Erreur lors de la suppression du produit.</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='message error'>❌ Erreur de suppression : " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestion des Produits</title>

  <link rel="stylesheet" href="../../style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* Variables de couleur */
    :root {
      --Rosepoudré: #f6f2eb;
      --Rosedragée: #d9e4d1;
      --Roseframboise: #6d9773;
      --Rosefuchsia: #3a5a40;
      --text-dark: #2d2d2d;
      --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      --radius: 16px;
      --transition: 0.3s ease;
    }

    /* ========== RESET ========== */
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--rose-light), var(--Rosedragée), var(--Roseframboise)); background-size: 400% 400%; animation: gradientFlow 10s ease infinite; min-height: 100vh; display: flex; justify-content: center; padding: 20px; }

    /* ========== Animations ========== */
    @keyframes gradientFlow { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

    /* ========== LAYOUT ========== */
    .app-container { display: flex; width: 100%; max-width: 1400px; min-height: 90vh; border-radius: var(--radius); box-shadow: var(--shadow); background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); flex-direction: column; animation: fadeInUp 1s ease; }

    /* ========== SIDEBAR ========== */
    .sidebar { width: 250px; background-color: var(--rose-dark); color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; background: url('../../images/sidebar.jpg') no-repeat; background-size: cover; background-position: center; }

    .sidebar .logo { font-size: 1.8em; font-weight: bold; text-align: center; color: var(--Rosepoudré); margin-bottom: 40px; }
    .sidebar .menu ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .sidebar .menu a { color: white; text-decoration: none; padding: 10px 15px; border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 12px; }
    .sidebar .menu a:hover { background-color: var(--Roseframboise); }

    /* ========== MAIN CONTENT ========== */
    .main-content { flex: 1; padding: 40px; animation: fadeInUp 1s ease; }
    .table-wrapper { max-width: 100%; margin: 0 auto; padding: 30px; border-radius: var(--radius); background-color: #ffffffcc; box-shadow: var(--shadow); }

    /* Table styles */
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; text-align: center; border: 1px solid var(--Rosepoudré); }
    th { background-color: var(--Rosefuchsia); color: white; }
    td { background-color: #f9f9f9; }
    td a { text-decoration: none; color: var(--Rosefuchsia); }
    td a:hover { color: var(--Roseframboise); }

    .message { text-align: center; font-weight: bold; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
    .message.success { background-color: #dff0d8; color: #3c763d; }
    .message.error { background-color: #f8d7da; color: #721c24; }

    /* Responsive */
    @media (max-width: 768px) { .table-wrapper { padding: 10px; } }
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
    <div class="menu">
      <ul>
        <li><a href="../../index.php"><i class="fa fa-home"></i> Dashboard</a></li>
        <li><a href="ajouter_produit.php"><i class="fa fa-plus"></i> Ajouter Produit</a></li>
        <li><a href="produits.php"><i class="fa fa-box"></i> Produits</a></li>
        <li><a href="clients.php"><i class="fa fa-user"></i> Clients</a></li>
        <li><a href="commandes.php"><i class="fa fa-shopping-cart"></i> Commandes</a></li>
        <li><a href="expeditions.php"><i class="fa fa-truck"></i> Expéditions</a></li>
        <li><a href="rapports.php"><i class="fa fa-chart-bar"></i> Rapports</a></li>
        <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </div>
  </aside>

  <!-- Contenu principal -->
  <main class="main-content">
    <div class="table-wrapper">
      <h1><i class="fa fa-box"></i> Liste des Produits</h1>

      <!-- Affichage du message -->
      <?php echo $message; ?>

      <!-- Table des produits -->
      <table>
        <thead>
          <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Catégorie</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produits as $produit): ?>
            <tr>
              <td><?= htmlspecialchars($produit['nom']); ?></td>
              <td><?= htmlspecialchars($produit['description']); ?></td>
              <td><?= number_format($produit['prix'], 2, ',', ' '); ?> €</td>
              <td><?= $produit['stock']; ?></td>
              <td><?= htmlspecialchars($produit['id_categorie']); ?></td>
              <td>
                <a href="edit_produit.php?id=<?= $produit['id_produit']; ?>" class="edit-btn">Edit</a>
                <a href="?delete=<?= $produit['id_produit']; ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

</div>

</body>
</html>
