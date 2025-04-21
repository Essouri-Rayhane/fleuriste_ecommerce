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
    .app-container { display: flex; width: 100%; max-width: 1400px; min-height: 90vh; border-radius: var(--radius); box-shadow: var(--shadow); background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); flex-direction: row; }

    /* ========== SIDEBAR ========== */
    .sidebar { width: 250px; background-color: var(--rose-dark); color: white; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; background: url('../../images/admin2.jpg') no-repeat; background-size: cover; background-position: center; }

    .sidebar .logo { font-size: 1.8em; font-weight: bold; text-align: center; color: var(--Rosepoudré); margin-bottom: 40px; }
    .sidebar .logo span{ font-size: 1.8em; font-weight: bold; text-align: center; color: var(--Rosepoudré); margin-bottom: 40px; }
    .sidebar .menu ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .sidebar .menu a { color: white; text-decoration: none; padding: 10px 15px; border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 12px; }
    .sidebar .menu a:hover { background-color: var(--Roseframboise); }

    /* ========== MAIN CONTENT ========== */
    .main-content { flex: 1; padding: 40px; animation: fadeInUp 1s ease; }

    /* Tableau des produits */
    .grid-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 30px; }
    .product-card { border-radius: var(--radius); box-shadow: var(--shadow); background-color: #fff; padding: 15px; text-align: center; transition: var(--transition); }
    .product-card:hover { transform: scale(1.05); }
    .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius); }
    .product-card h3 { font-size: 1.2em; margin: 10px 0; color: var(--Rosefuchsia); }
    .product-card p { color: #666; font-size: 0.9em; }

    /* Boutons de suppression et modification avec icônes */
    .action-buttons a { text-decoration: none; color: var(--Rosefuchsia); margin: 5px;   transition: var(--transition); }
    .action-buttons a:hover { color: var(--Roseframboise); transform: translateY(-3px); }
    .action-buttons i { font-size: 1.3em; }

    /* Messages */
    .message { text-align: center; font-weight: bold; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
    .message.success { background-color: #dff0d8; color: #3c763d; }
    .message.error { background-color: #f8d7da; color: #721c24; }


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

/* ========== LAYOUT ========== */
.app-container {
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

/* ========== SIDEBAR ========== */
.sidebar {
  width: 250px;
  background-color: var(--rose-dark);
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  animation: slideInLeft 0.8s ease;
  background: url('../../images/admin2.jpg') no-repeat;
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

/* ========== MAIN CONTENT ========== */
.main-content {
  flex: 1;
  padding: 40px;
  overflow-y: auto;
  animation: fadeInUp 1s ease;
}

.form-wrapper {
  max-width: 700px;
  margin: 0 auto;
  padding: 30px;
  border-radius: var(--radius);
  background-color: #ffffffcc;
  box-shadow: var(--shadow);
  animation: fadeInUp 1s ease;
}

.form-wrapper h2 {
  font-size: var(--h2-size);
  color: var(--rose-dark);
  text-align: center;
  margin-bottom: 25px;
}

/* Form Elements */
input, textarea, select {
  width: 100%;
  padding: 14px;
  margin-bottom: 20px;
  border: 1px solid var(--rose-medium);
  border-radius: 10px;
  font-size: 1rem;
  transition: var(--transition);
  background-color: white;
}

input:focus,
textarea:focus,
select:focus {
  border-color: var(--Roseframboise);
  box-shadow: 0 0 10px rgba(109, 151, 115, 0.5);
  outline: none;
}

/* Buttons */
button {
  width: 100%;
  padding: 14px;
  background-color: var(--Rosefuchsia);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: var(--transition);
}

button:hover {
  background-color: var(--Roseframboise);
  transform: translateY(-3px);
}

/* Messages */
.message {
  text-align: center;
  font-weight: bold;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.message.success {
  background-color: #dff0d8;
  color: #3c763d;
}

.message.error {
  background-color: #f8d7da;
  color: #721c24;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .app-container {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    flex-shrink: 0;
    padding: 15px;
    border-radius: 0;
    text-align: center;
  }

  .sidebar .menu ul {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
  }

  .main-content {
    padding: 20px;
  }

  .form-wrapper {
    padding: 20px;
  }
}


.drop-zone {
      border: 2px dashed #aaa;
      padding: 30px;
      text-align: center;
      color: #777;
      cursor: pointer;
      border-radius: 10px;
      transition: all 0.3s ease;
      margin-bottom: 20px;
      background-color: #fafafa;
    }


    .drop-zone.dragover {
      background-color: var(--Rosedragée);
      border-color: var(--Roseframboise);
    }


    .drop-zone span {
      display: block;
      margin-top: 10px;
      font-size: 0.9em;
      color: #444;
    }



    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 6px;
    }

    .message.success {
      background-color: #dff0d8;
      color: #3c763d;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
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
    <div class="menu">
      <ul>
        <li><a href="../../index.php"><i class="fa fa-home"></i> Accueil</a></li>
        <li><a href="ajouter_produit.php"><i class="fa fa-plus"></i> Ajouter Produit</a></li>
        <li><a href="produits.php"><i class="fa fa-box"></i> Produits</a></li>
        
        <li><a href="commandes.php"><i class="fa fa-shopping-cart"></i> Commandes</a></li>
        <li><a href="expeditions.php"><i class="fa fa-truck"></i> Expéditions</a></li>
        <li><a href="rapports.php"><i class="fa fa-chart-bar"></i> Rapports</a></li>
        <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </div>
  </aside>

  <!-- Contenu principal -->
  <main class="main-content">
    <h1><i class="fa fa-box"></i> Liste des Produits</h1>

    <!-- Affichage du message -->
    <?php echo $message; ?>

    <!-- Affichage des produits sous forme de grille -->
    <div class="grid-wrapper">
      <?php foreach ($produits as $produit): ?>
        <div class="product-card">
          <img src="../../images/produits/<?= $produit['image']; ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" />
          <h3><?= htmlspecialchars($produit['nom']); ?></h3>
          <p><?= htmlspecialchars($produit['description']); ?></p>
          <p><strong>Prix :</strong> <?= number_format($produit['prix'], 2, ',', ' '); ?> €</p>
          <p><strong>Stock :</strong> <?= $produit['stock']; ?></p>
          <div class="action-buttons">
            <a href="modifier_produit.php?id=<?= $produit['id_produit']; ?>"><i class="fa fa-pencil-alt"></i></a>
            <a href="?delete=<?= $produit['id_produit']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"><i class="fa fa-trash-alt"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

</div>

</body>
</html>
