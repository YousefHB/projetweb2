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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- FLATICON -->
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css"/>

    <!-- SWIPER CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- STYLES -->
    <link rel="stylesheet" href="assets/css/styles.css" />

    <title>Galerie Artistique</title>
    <style>
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
      }

      .home__subtitle {
        font-size: 1.2rem;
        color: hsl(176, 88%, 27%);
        font-weight: 600;
        margin-bottom: 1rem;
        display: block;
      }

      .home__title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
        color: #222;
      }

      .home__description {
        font-size: 1.1rem;
        color: #444;
        margin-bottom: 1.5rem;
        max-width: 600px;
        line-height: 1.6;
      }

      .btn {
        display: inline-block;
        background-color: hsl(176, 88%, 27%);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        transition: background-color 0.3s ease;
      }

      .btn:hover {
        background-color: hsl(176, 88%, 20%);
      }

      .home__img {
        max-width: 100%;
        height: auto;
        object-fit: cover;
      }

      .home {
        min-height: 100vh;
        display: flex;
        align-items: center;
      }

      .home__container {
        display: grid;
        align-items: center;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
      }
.button-container {
  text-align: center; /* centre le bouton horizontalement */
  margin: 20px 0;
}

.btn {
  display: inline-flex;         /* permet centrage vertical + horizontal */
  justify-content: center;      /* centre horizontalement */
  align-items: center;          /* centre verticalement */
  padding: 12px 28px;
    background-color:hsl(176, 88%, 27%);

  color: white;
  font-weight: 600;
  font-size: 16px;
  text-decoration: none;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0,123,255,0.3);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  user-select: none;
  text-align: center;           /* au cas où */
}


.btn:hover {
  background-color: white;
  text:  background-color:hsl(176, 88%, 27%);
 /* bleu plus foncé au survol */
  box-shadow: 0 6px 12px rgba(0,86,179,0.5);
}



.section--lg {
  padding-top: 10px !important;
  margin-top: 0 !important;
}

.header {
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}
.footer__description .email {
  display: block;
}
  strong {
    color: #FFAAAA;
  }
  .artistry{
        color: #FFAAAA;
        font-weight: bold;
        font-size: 1.5rem;

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

    <!--=============== HEADER ===============-->
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
  <li class="nav__item"><a href="index.php" class="nav__link active-link">Home</a></li>
  <li class="nav__item"><a href="shop.php" class="nav__link">Shop</a></li>

  <?php if ($connected): ?>
    <li class="nav__item"><a href="accounts.php" class="nav__link">My Account</a></li>
    <?php if ($role === "artiste"): ?>
      <li class="nav__item"><a href="compare.php" class="nav__link">Publication</a></li>
    <?php endif; ?>
  <?php else: ?>
    <li class="nav__item"><a href="login.php" class="nav__link">Se connecter</a></li>
    <li class="nav__item"><a href="login-register.php" class="nav__link">S'inscrire</a></li>
  <?php endif; ?>

  <?php if ($connected && $username === 'root'): ?>
    <li class="nav__item"><a href="admin.php" class="nav__link">Administration</a></li>
    <li class="nav__item"><a href="admin_statistiques.php" class="nav__link">Statistique</a></li>
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

    <!--=============== MAIN ===============-->
    <main class="main">
      <!--=============== HOME ===============-->
      <section class="home section--lg">
        <div class="home__container container grid">
          <div class="home__content">
            <span class="home__subtitle">Bienvenue chez <span class="artistry">Artistry</span>, où chaque âme vibre au rythme de l’art.</span>
            <h1 class="home__title">Découvrez les talents des artistes du numérique</h1>
            <p class="home__description">
              Une plateforme dédiée à l'exposition et à la vente de créations artistiques uniques : peintures, logos, montages, illustrations digitales et bien plus encore.
            </p>
            <p class="home__description">
              <em>« L'art, c'est l'homme ajouté à la nature. »</em><br>
              <cite>– Vincent Van Gogh</cite>
            </p>
            <div class="button-container">
    <a href="login.php" class="btn">Se connecter</a>
</div>

          </div>
          <img src="assets/img/backgroundindex.png" class="home__img" alt="Créations artistiques" />
        </div>
      </section>

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
<p class="footer__description">
  <span>Email:</span>
  <span class="email">Hbaieb.yousef@gmail.com</span>
  <span class="email">Malekneili66@gmail.com</span>
</p>


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
