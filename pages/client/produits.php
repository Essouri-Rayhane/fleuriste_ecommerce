<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Produits</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <!-- Entête de la page -->
    <header>
        <nav>
            <a href="index.php"><i class="fas fa-home"></i> Accueil</a>
            <a href="produit.php"><i class="fas fa-box-open"></i> Produits</a>
            <a href="clients.php"><i class="fas fa-users"></i> Clients</a>
            <a href="panier.php"><i class="fas fa-shopping-cart"></i>panier</a>
        </nav>
    </header>

    <!-- Section Produits -->
    <main>
        <h1>Nos Produits</h1>

        <!-- Formulaire de filtre -->
        <section class="filter">
            <label for="category">Catégorie:</label>
            <select id="category">
                <option value="all">Tous</option>
                <option value="electronics">Électroniques</option>
                <option value="furniture">Meubles</option>
                <option value="clothing">Vêtements</option>
            </select>

            <label for="price-range">Prix:</label>
            <input type="range" id="price-range" min="0" max="1000" step="10" value="500">
            <span id="price-value">500</span> €
        </section>

        <!-- Affichage des produits -->
        <section class="product-list">
            <div class="product-card">
                <img src="path_to_image.jpg" alt="Produit 1">
                <h3>Produit 1</h3>
                <p>Catégorie: Électronique</p>
                <p>Prix: 250 €</p>
                <button><i class="fas fa-cart-plus"></i> Ajouter au panier</button>
            </div>
            <!-- Ajouter plus de produits ici -->
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p><i class="fas fa-envelope"></i> Contactez-nous : support@example.com</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
