<?php include 'includes/header.php'; ?>

<div class="container">
    <h1 >Bienvenue sur </h1>
    <h2><span>Petalune </span> 🌸</h2> 
    <p><span>💐</span>Votre boutique en ligne pour toutes vos envies florales !Votre destination pour des bouquets élégants, des plantes rares et des cadeaux floraux sur-mesure <span>💐</span></p>

    <div class="menu-grid">
    <a href="pages/produits.php" class="card">
        <i class="fas fa-box-open"></i> Produits
    </a>
    <a href="pages/clients.php" class="card">
        <i class="fas fa-users"></i> Clients
    </a>
    <a href="pages/commandes.php" class="card">
        <i class="fas fa-shopping-cart"></i> Commandes
    </a>
    <a href="pages/expeditions.php" class="card">
        <i class="fas fa-truck"></i> Expéditions
    </a>
    <a href="pages/rapports.php" class="card">
        <i class="fas fa-chart-line"></i> Rapports
    </a>
</div>

    
</div>


<!-- SECTION about -->
<section class="about" id="about">

  <h1 class="heading"><span>About</span> Us</h1>

  <div class="row">

    <div class="video-container">
    <h3>Les Maîtres Fleuristes – L’art de sublimer chaque moment.</h3>
      <video src="images/about-vid.mp4" loop autoplay muted></video>
      
    </div>

    <div class="content">
      <h2>Pourquoi Petalune?</h2>
      
      <p>Spécialistes des fleurs de qualité, 
        nous créons des compositions florales uniques pour toutes vos occasions 
        :
         </p>
         <p> anniversaires, mariages, messages d’amour ou simples attentions. 
         Nos bouquets, préparés avec passion,</p>
<p>sont livrés avec soin pour faire naître l’émotion dès le premier regard.</p>
<a href="pages/a-propos.php" class="card-cmd">En savoir plus</a>

    </div>

  </div>

</section>
<!-- SECTION CATEGORIES -->
<section class="container2">
<h1 class="heading"><span>Nos</span> catégories</h1>
  
  
  <div class="menu-grid">
    <a href="pages/produits.php" class="card">
      <img src="images/fleurs.jpg" alt="Fleurs" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
      <p>Fleurs fraîches</p>
    </a>
    <a href="pages/produits.php" class="card">
      <img src="images/plantes.jpg" alt="Plantes" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
      <p>Plantes d’intérieur</p>
    </a>
    <a href="pages/produits.php" class="card">
      <img src="images/coffrets.jpg" alt="Coffrets" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
      <p>Coffrets cadeaux</p>
    </a>
    <a href="pages/produits.php" class="card">
      <img src="images/bouquet.jpg" alt="Bouquets" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
      <p>Bouquets personnalisés</p>
    </a>
    <a href="pages/produits.php" class="card">
      <img src="images/fleur-sechée.jpg" alt="fleur-sechée" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
      <p>Fleurs Séchées</p>
      
    </a>
  </div>
</section>



<!-- SECTION APPEL À L'ACTION -->
<section class="cta-section">
  <h2>Vous avez une occasion spéciale ?</h2>
  <p>Commandez un bouquet personnalisé ou contactez-nous pour plus de détails 💌</p>
  <a href="pages/commandes.php" class="card-cmd">Passer une commande</a>
  <a href="contact" class="card-cmd">contactez-nous</a>
</section>

<!-- SECTION contact-->
<section class="contact" id="contact">
    <h1 class="heading">
        <span>contactez</span>-nous
    </h1>

    <div class="row">
        <form action="">
            <input type="text" placeholder="name" class="box">
            <input type="email" placeholder="email" class="box">
            <input type="number" placeholder="number" class="box">
            <textarea name="" class="box" placeholder="message" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="send message" class="card-cmd">
        </form>

        <div class="image">
            <img src="images/contact-img.jpg" alt="Contact illustration">
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
