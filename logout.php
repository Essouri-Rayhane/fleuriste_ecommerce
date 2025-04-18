<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
    <meta http-equiv="refresh" content="5;url=login_client.php">
    <style>
        :root {
            --Rosepoudré: #f6f2eb;
            --Rosedragée: #d9e4d1;
            --Roseframboise: #6d9773;
            --Rosefuchsia: #3a5a40;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, var(--Rosedragée), var(--Rosepoudré));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1.5s ease-in;
        }

        .logout-box {
            background-color: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
            animation: slideUp 1s ease-out;
        }

        .logout-box h1 {
            font-size: 2.2rem;
            color: var(--Rosefuchsia);
            margin-bottom: 20px;
        }

        .logout-box p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 15px;
        }

        .logout-box span {
            font-size: 1rem;
            color: var(--Roseframboise);
            display: block;
            margin-top: 15px;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 15px;
            color: var(--Roseframboise);
            animation: bounce 1.5s infinite;
        }

        .countdown {
            font-weight: bold;
            color: var(--Rosefuchsia);
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .btn-redirect {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--Rosefuchsia);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-redirect:hover {
            background-color: var(--Roseframboise);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <div class="icon">👋</div>
        <h1>Vous êtes déconnecté(e)</h1>
        <p>Merci pour votre visite, à très bientôt !</p>
        <div class="countdown">Redirection dans <span id="count">5</span> secondes...</div>
        <a href="login_client.php" class="btn-redirect">Se reconnecter maintenant</a>
    </div>

    <script>
        let count = 5;
        const countdownElement = document.getElementById("count");
        const interval = setInterval(() => {
            count--;
            if (count <= 0) {
                clearInterval(interval);
            } else {
                countdownElement.textContent = count;
            }
        }, 1000);
    </script>
</body>
</html>
