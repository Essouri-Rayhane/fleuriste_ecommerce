<?php
include('../../config/db.php');
session_start();

$message = "";
// Inclure la connexion à la base de données


// Récupérer les catégories depuis la base
try {
    $stmt = $conn->query("SELECT id_categorie, nom_categorie FROM categorie");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='message error'>❌ Erreur de chargement des catégories : " . $e->getMessage() . "</div>";
    $categories = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajouter_produit"])) {

    $nom = $_POST["nom"];
    $description = $_POST["description"];
    $prix = $_POST["prix"];
    $stock = $_POST["stock"];
    $categorie = $_POST["categorie"];

    $targetDir = "../../images/produits/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Utilisation de la bonne table : produit (pas produits)



            $stmt = $conn->prepare("INSERT INTO produit (nom, description, prix, stock, image, id_categorie) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $description, $prix, $stock, $imageName, $categorie])) {
                $message = "<div class='message success'>✅ Produit ajouté avec succès !</div>";
            } else {
                $message = "<div class='message error'>❌ Erreur lors de l'ajout en base.</div>";
            }
            $stmt = null; // ✅ optionnel mais correct en PDO

        } else {
            $message = "<div class='message error'>❌ Erreur lors de l’upload de l’image.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ Veuillez sélectionner une image valide.</div>";
    }
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ajouter un produit</title>

  <!-- Feuilles de style -->
  <link rel="stylesheet" href="../../style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
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
    <div class="form-wrapper">
      <h1><i class="fa fa-plus-circle"></i> Ajouter un produit</h1>

      <!-- Affichage du message -->
      <?php echo $message; ?>

      <!-- Formulaire d'ajout -->
      <form method="POST" enctype="multipart/form-data">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Description :</label>
        <textarea name="description" rows="4"></textarea>

        <label>Prix :</label>
        <input type="number" name="prix" min="0" step="0.01" required>

        <label>Stock :</label>
        <input type="number" name="stock" min="0" required>

        <label>Image :</label>
        <div class="drop-zone" id="dropZone">
          Glissez-déposez une image ici ou cliquez pour choisir
          <input type="file" name="image" id="fileInput" accept="image/*" style="display:none;" required>
          <span id="file-name"></span>
        </div>

        

        <label>Catégorie :</label>
        <select name="categorie" >
         <option value="">-- Choisir une catégorie --</option>
         <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id_categorie']; ?>">
            <?= htmlspecialchars($cat['nom_categorie']); ?>
        </option>
    <?php endforeach; ?>
    </select><br><br>


 


           
        
        <button type="submit" name="ajouter_produit">Ajouter le produit</button>
        
      </form>
    </div>
  </main>
</div>

<!-- Script JS drag & drop -->
<script>
  const dropZone = document.getElementById("dropZone");
  const fileInput = document.getElementById("fileInput");
  const fileName = document.getElementById("file-name");

  dropZone.addEventListener("click", () => fileInput.click());

  fileInput.addEventListener("change", () => {
    if (fileInput.files.length > 0) {
      fileName.textContent = fileInput.files[0].name;
    }
  });

  dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragover");
  });

  dropZone.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragover");
  });

  dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragover");
    if (e.dataTransfer.files.length > 0) {
      fileInput.files = e.dataTransfer.files;
      fileName.textContent = e.dataTransfer.files[0].name;
    }
  });
</script>

</body>
</html>
