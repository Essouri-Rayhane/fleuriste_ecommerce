<?php
require_once 'config/db.php'; 
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM client WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "<p class='error'>Cet email est déjà utilisé.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO client (nom, prenom, email, adresse, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $adresse, $mot_de_passe]);

        header("Location: login_client.php");
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
    --h2-size: clamp(2rem, 5vw, 3rem);
}

body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(145deg, #f9e7e9, #f1f1f1);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

form {
  background-color: rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(10px);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 90%;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

form h2 {
  font-size: var(--h2-size);
  color: var(--Rosefuchsia);
  text-align: center;
  margin-bottom: 25px;
}
form p.intro {
  font-size: 1rem;
  color: var(--Roseframboise);
  margin-bottom: 25px;
  margin-left: 45px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid var(--Roseframboise);
  border-radius: 12px;
  background-color: #fff;
  font-size: 1rem;
  margin-bottom: 20px;
  transition: 0.3s ease;
}

input:focus {
  border-color: var(--Rosefuchsia);
  box-shadow: 0 0 6px var(--Rosefuchsia);
  outline: none;
}

button {
  width: 100%;
  background-color: var(--Rosefuchsia);
  color: white;
  padding: 14px;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  cursor: pointer;
  transition: 0.3s ease;
}

button:hover {
  background-color: var(--Roseframboise);
  transform: translateY(-2px);
}

.error {
  color: #b33a3a;
  background-color: #ffe6e6;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 20px;
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
</style>

<form method="POST">
    <h2>Inscription Client</h2>
    <p class="intro">Crée ton compte pour rejoindre notre monde fleurie 🌸🌿</p>

    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    
    <?php if (!empty($message)) echo $message; ?>

    <input type="text" name="adresse" placeholder="Adresse" required>
    <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>

    <div class="password-toggle">
        <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()"> 
        <label for="togglePassword">Afficher le mot de passe</label>
    </div>

    <button type="submit">S'inscrire</button>
</form>

<script>
function togglePasswordVisibility() {
  const passwordInput = document.getElementById("mot_de_passe");
  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}
</script>
