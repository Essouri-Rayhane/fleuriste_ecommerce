<?php
session_start();
require_once('config/db.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        $_SESSION['admin'] = $admin['id_admin'];
        header("Location: pages/admin/produits.php");
        exit();
    } else {
        $message = "<p class='error'>❌ Identifiants incorrects</p>";
    }
}
?>
 <style>
        :root {
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--Rosedragée), var(--Rosepoudré));
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        form h2 {
            font-size: var(--h2-size);
            color: var(--rose-dark);
            margin-bottom: 20px;
            text-align: center;
        }

        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--Roseframboise);
            border-radius: 12px;
            background-color: #fff;
            font-size: 1rem;
            margin-bottom: 18px;
            transition: 0.3s;
        }

        form input:focus {
            border-color: var(--Rosefuchsia);
            box-shadow: 0 0 8px var(--Rosefuchsia);
            outline: none;
        }

        form button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: var(--Rosefuchsia);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        form button:hover {
            background-color: var(--Roseframboise);
            transform: translateY(-2px);
        }

        .error {
            color: #a94442;
            background: #f8d7da;
            border-left: 5px solid #a94442;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 0.95rem;
            color: #555;
        }

        .footer-text a {
            color: var(--rose-dark);
            text-decoration: none;
            font-weight: bold;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Connexion Admin</h2>

    <?php if (!empty($message)) echo $message; ?>

    <input type="email" name="email" placeholder="Adresse email" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>

    <div class="footer-text">
        Vous n'avez pas de compte ? <a href="register_admin.php">Créer un compte</a>
    </div>
</form>