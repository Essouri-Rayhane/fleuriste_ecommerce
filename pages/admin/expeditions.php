<?php
include('../../config/db.php');
session_start();

$message = "";

// Suppression d'une expédition
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt = $conn->prepare("DELETE FROM expedition WHERE id_expedition = ?");
    if ($stmt->execute([$id])) {
        $message = "<div class='message success'><i class='fas fa-check-circle'></i> Expédition supprimée avec succès.</div>";
    } else {
        $message = "<div class='message error'><i class='fas fa-exclamation-circle'></i> Erreur lors de la suppression.</div>";
    }
}

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_statut'])) {
    $id = $_POST['id_expedition'];
    $statut = $_POST['statut_livraison'];
    $stmt = $conn->prepare("UPDATE expedition SET statut_livraison = ? WHERE id_expedition = ?");
    if ($stmt->execute([$statut, $id])) {
        $message = "<div class='message success'><i class='fas fa-check-circle'></i> Statut mis à jour avec succès.</div>";
    } else {
        $message = "<div class='message error'><i class='fas fa-exclamation-circle'></i> Échec de la mise à jour du statut.</div>";
    }
}

// Récupérer les expéditions
$stmt = $conn->query("
    SELECT e.*, c.nom, c.prenom 
    FROM expedition e
    JOIN commande cmd ON e.id_commande = cmd.id_commande
    JOIN client c ON cmd.id_client = c.id_client
    ORDER BY e.date_expedition DESC
");
$expeditions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Expéditions</title>
  <link rel="stylesheet" href="../../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    <?php include('../../style.css'); ?>




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













    /* -------------------------------------------------------------------------------------------------- */
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

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, var(--Rosepoudré), var(--Rosedragée), var(--Roseframboise));
      background-size: 400% 400%;
      animation: gradientFlow 10s ease infinite;
      min-height: 100vh;
      padding: 20px;
    }

    @keyframes gradientFlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .app-container {
      display: flex;
      width: 100%;
      max-width: 1400px;
      min-height: 90vh;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(12px);
      overflow: hidden;
      margin: 0 auto;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: url('../../images/admin1.jpg') no-repeat center/cover;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar .logo {
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

    .sidebar .menu a:hover {
      background-color: var(--Roseframboise);
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .form-wrapper {
      width: 100%;
      max-width: 100%;
    }

    h1 {
      color: var(--Rosefuchsia);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: var(--shadow);
      border-radius: var(--radius);
      overflow: hidden;
      margin-top: 20px;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: var(--Roseframboise);
      color: white;
      position: sticky;
      top: 0;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    /* Form Elements in Table */
    select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 6px;
      width: 100%;
    }

    .status-form {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .status-form button {
      padding: 8px 12px;
      background-color: var(--Roseframboise);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: var(--transition);
    }

    .status-form button:hover {
      background-color: var(--Rosefuchsia);
    }

    /* Action Buttons */
    .action-buttons a {
      color: var(--Rosefuchsia);
      margin: 0 5px;
      transition: var(--transition);
    }

    .action-buttons a:hover {
      color: var(--Roseframboise);
    }

    /* Messages */
    .message {
      text-align: center;
      font-weight: bold;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .app-container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
      }

      .main-content {
        padding: 20px;
      }
    }

    @media (max-width: 768px) {
      table {
        display: block;
        overflow-x: auto;
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
    <div class="menu">
      <ul>
        <li><a href="../../index.php"><i class="fa fa-home"></i> Accueil</a></li>
        <li><a href="ajouter_produit.php"><i class="fa fa-plus"></i> Ajouter Produit</a></li>
        <li><a href="produits.php"><i class="fa fa-box"></i> Produits</a></li>
        
        <li><a href="commandes.php"><i class="fa fa-shopping-cart"></i> Commandes</a></li>
        <li><a href="expeditions.php" class="active"><i class="fa fa-truck"></i> Expéditions</a></li>
        <li><a href="rapports.php"><i class="fa fa-chart-bar"></i> Rapports</a></li>
        <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </div>
  </aside>

  <div class="main-content">
    <div class="form-wrapper">
      <h1><i class="fas fa-shipping-fast"></i> Gestion Expéditions</h1>

      <?= $message ?>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Date Expédition</th>
            <th>Adresse</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($expeditions as $exp) : ?>
          <tr>
            <td><?= $exp['id_expedition'] ?></td>
            <td><?= htmlspecialchars($exp['nom']) . ' ' . htmlspecialchars($exp['prenom']) ?></td>
            <td><?= $exp['date_expedition'] ?></td>
            <td><?= nl2br(htmlspecialchars($exp['adresse_livraison'])) ?></td>
            <td>
              <form method="post" class="status-form">
                <input type="hidden" name="id_expedition" value="<?= $exp['id_expedition'] ?>">
                <select name="statut_livraison">
                  <option <?= $exp['statut_livraison'] == 'En préparation' ? 'selected' : '' ?>>En préparation</option>
                  <option <?= $exp['statut_livraison'] == 'En cours' ? 'selected' : '' ?>>En cours</option>
                  <option <?= $exp['statut_livraison'] == 'Livrée' ? 'selected' : '' ?>>Livrée</option>
                </select>
                <button type="submit" name="update_statut"><i class="fas fa-check"></i></button>
              </form>
            </td>
            <td class="action-buttons">
              <a href="?supprimer=<?= $exp['id_expedition'] ?>" onclick="return confirm('Supprimer cette expédition ?')">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (count($expeditions) === 0): ?>
            <tr>
              <td colspan="6" style="text-align:center;">Aucune expédition trouvée.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>