<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Petalune</title>
 
<!-- Font Awesome CDN Link -->
<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
    <div class="logo">
        <img src="images/logo.png" alt="Petalume" height="200" >
        <h2>Bloom<span>&</span>Co</h2>
    </div>
</div>
    <nav class="main-nav">
        <ul class="sidebar">
        <li onclick=hidesidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="ndefined"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="../pages/produits.php">Produits</a></li>
            <li><a href="../pages/clients.php">Clients</a></li>
            <li><a href="../pages/commandes.php">Commandes</a></li>
            <li><a href="../pages/expeditions.php">Expéditions</a></li>
            <li><a href="../pages/rapports.php">Rapports</a></li>

        </ul>
        <ul>
            <li class="hideOnMobile"><a href="index.php">Accueil</a></li>
            <li class="hideOnMobile"><a href="../pages/produits.php">Produits</a></li>
            <li class="hideOnMobile"><a href="../pages/clients.php">Clients</a></li>
            <li class="hideOnMobile"><a href="../pages/commandes.php">Commandes</a></li>
            <li class="hideOnMobile"><a href="../pages/expeditions.php">Expéditions</a></li>
            <li class="hideOnMobile"><a href="../pages/rapports.php">Rapports</a></li>
            <li class="menu-btn" onclick=showsidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="ndefined"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>

        </ul>
    </nav>
    <script>
        function showsidebar(){
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display='flex'
        }
        function hidesidebar(){
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display='none'
        }
    </script>
</header>

<main>
