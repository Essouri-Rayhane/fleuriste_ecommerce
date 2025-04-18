<?php
require_once 'config/db.php'; 
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "<p class='error-message'>🚫 Cet email est déjà utilisé. Veuillez en choisir un autre.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $mot_de_passe]);
        header("Location: login_admin.php");
        exit();
    }
}
?>
<style>
:root {
  --pink: #a3c4a8;
  --Rosepoudré: #f6f2eb;
  --Rosedragée: #d9e4d1;
  --Roseframboise: #6d9773;
  --Rosefuchsia: #3a5a40;

  --rose-light: #FDE2E4;
  --rose: #f9c5d1;
  --rose-medium: #f7a1b0;
  --rose-dark: #c97b8d;
  --rose-deep: #8b4d5d;
  --rose-gold: #b76e79;
  --blush: #f4acb7;
  --champagne: #fcd5ce;

  --h2-size: clamp(2rem, 5vw, 3rem);
}

body {
  background: linear-gradient(145deg, #f9e7e9, #f1f1f1);
  font-family: 'Segoe UI', sans-serif;
  margin: 0;
  padding: 0;
}

form {
  background-color: rgba(255, 255, 255, 0.65);
  backdrop-filter: blur(10px);
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  max-width: 550px;
  margin: 50px auto;
  animation: fadeIn 1.2s ease;
  text-align: center;
}

/* Animation légère */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Titre doux */
form h2 {
  font-size: var(--h2-size);
  color: var(--Rosefuchsia);
  margin-bottom: 10px;
}

form p.intro {
  font-size: 1rem;
  color: var(--Roseframboise);
  margin-bottom: 25px;
}

/* Champs */
form input[type="text"],
form input[type="email"],
form input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 20px;
  border: 2px solid var(--Roseframboise);
  border-radius: 12px;
  background-color: #fff;
  font-size: 1rem;
  transition: 0.3s;
}

form input:focus {
  border-color: var(--Rosefuchsia);
  box-shadow: 0 0 8px var(--Roseframboise);
  outline: none;
}

/* Bouton */
form button {
  background-color: var(--Rosefuchsia);
  color: #fff;
  padding: 12px 25px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s ease;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

form button:hover {
  background-color: var(--Roseframboise);
  transform: scale(1.05);
}

/* Message d’erreur */
.error-message {
  color: #c0392b;
  font-weight: bold;
  margin-bottom: 15px;
  text-align: center;
}
/* Checkbox afficher le mot de passe */
.password-toggle {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  color: var(--Rosefuchsia);
  font-size: 0.95rem;
}
/* Responsive */
@media (max-width: 600px) {
  form {
    padding: 25px;
  }
}
</style>

<form method="POST">
  <h2>Bienvenue, futur Admin !</h2>
  <p class="intro">Crée ton compte pour rejoindre l’espace d’administration ✨</p>
  <input type="text" name="nom" placeholder="Ton prénom ou pseudo" required>
  <input type="email" name="email" placeholder="Ton adresse email" required>
  <?php if (!empty($message)) echo $message; ?>
  <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Choisis un mot de passe sécurisé" required>
<div class="password-toggle">
  <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()"> 
  <label for="togglePassword">Afficher le mot de passe</label>
</div>

  <button type="submit">S'inscrire</button>
</form>
<script>
function togglePasswordVisibility() {
    // document.getElementById("mot_de_passe") : récupère l’élément <input> du mot de passe.
    // passwordInput.type : c’est l’attribut type du champ (soit "password", soit "text").
  const passwordInput = document.getElementById("mot_de_passe");
  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        //   Si c’est masqué ("password"), on le rend visible ("text"),
        // sinon on le re-cache ("password").
}
</script>
