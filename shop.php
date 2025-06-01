<?php
require_once 'session.php';
require_once 'pdo.php';
require_once 'art.class.php';
require_once('user.class.php');


$cnx = new connexion();
$pdo = $cnx->CNXbase();

$art = new art($pdo);

$categories = $art->getCategories();
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
            <li class="nav__item"><a href="index.php" class="nav__link">Home</a></li>
            <li class="nav__item"><a href="shop.php" class="nav__link active-link">Shop</a></li>
             <?php if ($connected) : ?>
    <li class="nav__item">
      <a href="accounts.php" class="nav__link">My Account</a>
    </li>
  <?php endif; ?>

            <?php if ($connected && $role === "artiste") : ?>
              <li class="nav__item"><a href="compare.php" class="nav__link">Publication</a></li>
            <?php endif; ?>
            <li class="nav__item"><a href="login.php" class="nav__link">Se connecter</a></li>
                        <li class="nav__item"><a href="login-register.php" class="nav__link">s'inscrire</a></li>

          </ul>
        </div>
        <div class="header__user-actions">
          <a href="wishlist.php" class="header__action-btn" title="Wishlist">
            <img src="assets/img/icon-heart.svg" alt="" /><span class="count">3</span>
          </a>
          <a href="cart.php" class="header__action-btn" title="Cart">
            <img src="assets/img/icon-cart.svg" alt="" /><span class="count">3</span>
          </a>
        </div>
      </nav>
    </header>
<main class="main container section">
<?php if (empty($categories)): ?>
  <p class="no-products">Aucune catégorie trouvée.</p>
<?php else: ?>
  <?php foreach ($categories as $categorie): ?>
    <h2 class="section__title"><?= htmlspecialchars($categorie) ?></h2>

    <?php
    $art->category = $categorie;
    $produits = $art->getByCategory();
    if (empty($produits)):
    ?>
      <p class="no-products">Aucun produit dans cette catégorie.</p>
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
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add to Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Compare">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
                <!-- Ajouter au panier -->
                <a href="add_to_cart.php?id=<?= $prod->ID_artwork ?>" class="action__btn" aria-label="Ajouter au panier">
                  <i class="fi fi-rs-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product__content">
              <span class="product__category"><?= htmlspecialchars($prod->category) ?></span>
              <h3 class="product__title"><?= htmlspecialchars($prod->title) ?></h3>
              <div class="product__price"><?= number_format($prod->price, 2, ',', ' ') ?> €</div>
              <div class="product__creator">Créé par : <strong><?= htmlspecialchars($createur) ?></strong></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
</main>


</body>
</html>
