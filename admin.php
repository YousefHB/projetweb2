<?php
require_once 'session.php';
require_once 'pdo.php';
require_once 'art.class.php';

$cnx = new connexion();
$pdo = $cnx->CNXbase();

$art = new art($pdo);

// Récupérer uniquement les œuvres non approuvées
$produits = $art->getnotapproverArt();
?>

<?php
require_once "session.php";

$userID = get_user_id();
$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$role = $connected ? ($_SESSION["role"] ?? '') : '';

require_once "user.class.php";
$user = new User();
$currentUser = $user->getUserByID($userID);


?>
<?php
require_once "session.php"; // session_start() est déjà dans ce fichier

$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$username = $connected ? htmlspecialchars($_SESSION["username"] ?? 'Utilisateur') : '';
$role = $connected ? ($_SESSION["role"] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Shop</title>

  <!--=============== FLATICON ===============-->
  <link
    rel="stylesheet"
    href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css"
  />

  <!--=============== SWIPER CSS ===============-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
  />

  <!--=============== CSS ===============-->
  <link rel="stylesheet" href="./assets/css/styles.css" />
  <style>
    /* Augmenter légèrement la taille de la carte produit */
.product__item {
  padding: 1.5rem;
  border: 1px solid #eee;
  border-radius: 16px;
  background-color: #fff;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.product__item:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

/* Agrandir un peu l'image */
.product__img {
  height: 260px;
  object-fit: cover;
  border-radius: 12px;
  transition: transform 0.3s ease;
}

/* Si tu veux un effet de zoom au survol */
.product__img.hover:hover {
  transform: scale(1.05);
}

/* Affichage du créateur */
.product__creator {
  margin-top: 8px;
  font-size: 0.9rem;
  color: #666;
}
.section--lg {
  padding-top: 0;
  margin-top: 0;
}
  strong {
    color: #FFAAAA;
  }


    </style>
</head>
<body>
  <?php if ($connected): ?>
      <div style="background-color: #f1f1f1; padding: 10px; text-align: center; font-family: sans-serif;">
        Bonjour <strong><?= $username ?></strong> !
        <a href="deconnexion.php" style="margin-left: 1rem;">Déconnexion</a>
      </div>
    <?php endif; ?>


    <header class="header">
      <nav class="nav container">
        <a href="index.php" class="nav__logo">
   <img
            class="nav__logo-img"
            src="assets/img/logoart.png"
            alt="website logo"
          />        </a>
        <div class="nav__menu" id="nav-menu">
 <ul class="nav__list">
  <li class="nav__item"><a href="index.php" class="nav__link ">Home</a></li>
  <li class="nav__item"><a href="shop.php" class="nav__link">Shop</a></li>

  <?php if ($connected && $username === 'root'): ?>
    <li class="nav__item"><a href="accounts.php" class="nav__link ">My Account</a></li>
    <?php if ($role === "artiste"): ?>
      <li class="nav__item"><a href="compare.php" class="nav__link">Publication</a></li>
    <?php endif; ?>
  <?php else: ?>
    <li class="nav__item"><a href="login.php" class="nav__link">Se connecter</a></li>
    <li class="nav__item"><a href="login-register.php" class="nav__link">S'inscrire</a></li>
  <?php endif; ?>

  <?php if ($connected && $username === 'root'): ?>
    <li class="nav__item"><a href="admin.php" class="nav__link active-link">Administration</a></li>
    <li class="nav__item"><a href="admin_statistiques.php" class="nav__link ">Statistique</a></li>
  <?php endif; ?>
</ul>
        </div>
        <div class="header__user-actions">
         
          <a href="cart.php" class="header__action-btn" title="Cart">
            <img src="assets/img/icon-cart.svg" alt="" /><span class="count">3</span>
          </a>
        </div>
      </nav>
    </header>
<main class="main container section">

<?php if (empty($produits)): ?>
  <p class="no-products">Aucune œuvre non approuvée pour le moment.</p>
<?php else: ?>
  <div class="products__container grid">
    <?php foreach ($produits as $prod): ?>
      <?php 
        // Récupérer le nom complet du créateur
        $createur = $art->getNomCreateur($prod->created_by);
      ?>
      <div class="product__item">
        <div class="product__banner">
          <a href="details.html" class="product__images">
            <img
              src="imagesart/<?= htmlspecialchars($prod->img_art) ?>"
              alt="<?= htmlspecialchars($prod->title) ?>"
              class="product__img default"
            />
            <img
              src="imagesart/<?= htmlspecialchars($prod->img_art) ?>"
              alt="<?= htmlspecialchars($prod->title) ?>"
              class="product__img hover"
            />
          </a>
        </div>
        <div class="product__content">
          <span class="product__category"><?= htmlspecialchars($prod->category) ?></span>
          <h3 class="product__title"><?= htmlspecialchars($prod->title) ?></h3>
          <div class="product__price"><?= number_format($prod->price, 2, ',', ' ') ?> €</div>
          <div class="product__creator">Créé par : <strong><?= htmlspecialchars($createur) ?></strong></div>

          <!-- Formulaire pour approuver/refuser -->
          <form method="post" action="approve_action.php" style="margin-top: 1rem; display: flex; gap: 1rem; align-items: center;">
  <input type="hidden" name="id_artwork" value="<?= $prod->ID_artwork ?>" />

  <button type="submit" name="action" value="approve"
    style="background-color: hsl(176, 88%, 27%); color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer;">
    Approuver
  </button>

  <button type="submit" name="action" value="reject"
    style="background-color: #E73CB9FF; color: white; padding: 0.5rem 1rem; border: none; border-radius: 5px; cursor: pointer;">
    Ne pas approuver
  </button>
</form>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</main>


 <!--=============== NEWSLETTER ===============-->
      <section class="newsletter section home__newsletter">
        <div class="newsletter__container container grid">
          <h3 class="newsletter__title flex">
            <img src="./assets/img/icon-email.svg" alt="" class="newsletter__icon" />
            Inscrivez-vous à Artistry
          </h3>
          <p class="newsletter__description">...et recevez un coupon de 25$ pour votre premier achat.</p>
          <form action="" class="newsletter__form">
            <input type="text" placeholder="Entrez votre e-mail" class="newsletter__input" />
            <a href="login-register.php" class="newsletter__btn">S'inscrire</a>
          </form>
        </div>
      </section>
    </main>

    <!--=============== FOOTER ===============-->
    <footer class="footer container">
      <div class="footer__container grid">
        <div class="footer__content">
          <a href="index.php" class="footer__logo">
            <img src="assets/img/logoart.png" alt="" class="footer__logo-img" />
          </a>
          <h4 class="footer__subtitle">Contact</h4>
          <p class="footer__description"><span>Adresse:</span> Sfax,Tunisie</p>
          <p class="footer__description"><span>Téléphone:</span> (+216)51 267 554/(+216)24 129 525</p>
          <p class="footer__description"><span>Email:</span>Hbaieb.yousef@gmail.com</p>

          <div class="footer__social">
            <h4 class="footer__subtitle">Suivez-nous</h4>
            <div class="footer__links flex">
              <a href="#"><img src="./assets/img/icon-facebook.svg" class="footer__social-icon" /></a>
              <a href="#"><img src="./assets/img/icon-twitter.svg" class="footer__social-icon" /></a>
              <a href="#"><img src="./assets/img/icon-instagram.svg" class="footer__social-icon" /></a>
              <a href="#"><img src="./assets/img/icon-pinterest.svg" class="footer__social-icon" /></a>
              <a href="#"><img src="./assets/img/icon-youtube.svg" class="footer__social-icon" /></a>
            </div>
          </div>
        </div>
        <div class="footer__content">
          <h3 class="footer__title">Informations</h3>
          <ul class="footer__links">
            <li><a href="#" class="footer__link">À propos</a></li>
            <li><a href="#" class="footer__link">Livraison</a></li>
            <li><a href="#" class="footer__link">Politique de confidentialité</a></li>
            <li><a href="#" class="footer__link">Conditions générales</a></li>
          </ul>
        </div>
      </div>
    </footer>
</body>
</html>
