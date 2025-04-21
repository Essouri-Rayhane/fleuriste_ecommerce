<?php include 'includes/header.php'; ?>

<div class="container">
    <h1 >Bienvenue sur </h1>
    <h2><span>Petalune </span> 🌸</h2> 
    <p><span>💐</span>Votre boutique en ligne pour toutes vos envies florales !Votre destination pour des bouquets élégants, des plantes rares et des cadeaux floraux sur-mesure <span>💐</span></p>

    <div class="menu-grid">
    <a href="#" class="card" onclick="openPopup('login')">
        <i class="fas fa-sign-in-alt"></i> Login
    </a>
    <a href="#" class="card" onclick="openPopup('register')">
        <i class="fas fa-user-plus"></i> Register
    </a>
    <div id="popup" class="modal">
  <div class="modal-content">
    <h2 id="popup-title">Choisissez Le Type De Compte</h2>
    <div class="modal-buttons">
      <button onclick="redirectTo('admin')" class="btn">Admin</button>
      <button onclick="redirectTo('client')" class="btn">Client</button>
    </div>
    <button class="btn-close" onclick="closePopup()">Fermer</button>
  </div>
</div>
</div>

<!-- Popup Modal -->
<!-- Popup Modal -->
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
      
      <p><i class="fas fa-check"></i> Fleurs fraîches de qualité exceptionnelle</p>
                    <p><i class="fas fa-check"></i> Compositions uniques et personnalisées</p>
                    <p><i class="fas fa-check"></i> Service livraison soigné et rapide</p>
                    <p><i class="fas fa-check"></i> Équipe de fleuristes passionnés</p>
                </div>
                <p class="highlight">Nos bouquets, préparés avec passion, sont livrés avec soin pour faire naître l'émotion dès le premier regard.</p>
                <a href="pages/a-propos.php" class="btn btn-primary">En savoir plus <i class="fas fa-arrow-right"></i></a>

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
  <a href="pages/client/produits.php" class="card-cmd">Passer une commande <i class="fas fa-shopping-basket"></i></a>
  <a href="#contact" class="card-cmd">Contactez-nous <i class="fas fa-envelope"></i></a>
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
<script>
   let currentAction = '';

function openPopup(action) {
  currentAction = action;
  document.getElementById('popup-title').textContent = 
    action === 'login' ? "Se Connecter Comme :" : "Créer Un Compte Comme :";
  document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
  document.getElementById('popup').style.display = 'none';
}

function redirectTo(role) {
  const action = currentAction;
  const url = (action === 'login')
    ? (role === 'admin' ? 'login_admin.php' : 'login_client.php')
    : (role === 'admin' ? 'register_admin.php' : 'register_client.php');

  window.location.href = url;
}

</script>

<?php include 'includes/footer.php'; ?>
