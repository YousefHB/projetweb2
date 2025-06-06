
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

  <!--=============== FLATICON ===============-->
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css" />

  <!--=============== SWIPER CSS ===============-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <!--=============== CSS ===============-->
  <link rel="stylesheet" href="./assets/css/styles.css" />
  <title>Créer un compte</title>

  <style>
    /* Styles du formulaire */
    .form {
      display: grid;
      gap: 1rem;
      padding: 2rem;
      background-color: #f9f9f9;
      border-radius: 8px;
    }
    .form__input {
      width: 100%;
      padding: 0.8rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
      margin-bottom: 1rem;
    }
    .form__input:focus {
      outline: none;
      border-color: #007bff;
    }
    .form__btn {
      display: flex;
      justify-content: center;
      margin-top: 1.5rem;
    }
    .btn {
      background-color: hsl(176, 88%, 27%);
      color: white;
      padding: 0.8rem 2rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
    }
    .btn:hover {
      background-color: hsl(176, 88%, 27%);
    }
    .create-account .section__title {
      text-align: center;
      font-size: 1.8rem;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }
    .art-touch {
      text-align: center;
      font-style: italic;
      color: #6c757d;
      margin-top: 1.5rem;
    }
    #notification {
      display: none;
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #28a745;
      color: white;
      padding: 1rem 2rem;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      z-index: 1000;
      font-family: sans-serif;
    }
  </style>
</head>
<body>

<!--=============== HEADER ===============-->
<header class="header">
  <nav class="nav container">
    <a href="index.php" class="nav__logo">
      <img class="nav__logo-img" src="assets/img/logoart.png" alt="Logo du site" />
    </a>
    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">
        <li class="nav__item"><a href="index.php" class="nav__link">Home</a></li>
        <li class="nav__item"><a href="shop.php" class="nav__link">Shop</a></li>
        <?php if ($connected): ?>
          <li class="nav__item"><a href="accounts.php" class="nav__link">My Account</a></li>
        <?php endif; ?>
        <?php if ($connected && $role === "artiste"): ?>
          <li class="nav__item"><a href="compare.php" class="nav__link">Publication</a></li>
        <?php endif; ?>
        <li class="nav__item"><a href="login.php" class="nav__link">Se connecter</a></li>
        <li class="nav__item"><a href="login-register.php" class="nav__link active-link">S'inscrire</a></li>
                                
                         <li class="nav__item">
<?php if ($connected && $username === 'root'): ?>
  <a href="admin.php" class="nav__link">Administration</a>
<?php endif; ?>
</li>


                         <li class="nav__item">
<?php if ($connected && $username === 'root'): ?>
  <a href="admin_statistiques.php" class="nav__link">Statistique</a>
<?php endif; ?>
</li>
      </ul>
    </div>
    <div class="header__user-actions">
   
      <a href="cart.php" class="header__action-btn" title="Cart">
        <img src="assets/img/icon-cart.svg" alt="" />
        <span class="count">3</span>
      </a>
    </div>
  </nav>
</header>

<!--=============== MAIN ===============-->
<main class="main">
  <!--=============== BREADCRUMB ===============-->
  <section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
      <li><a href="index.php" class="breadcrumb__link">Home</a></li>
      <li><span class="breadcrumb__link">></span></li>
      <li><span class="breadcrumb__link">Login / Register</span></li>
    </ul>
  </section>

  <!--=============== FORMULAIRE ===============-->
  <section class="login-register section--lg">
    <div class="login-register__container container grid">
      <div class="create-account">
        <h3 class="section__title">Créer un compte</h3>
        <form class="form grid formUser" method="post" enctype="multipart/form-data" action="enregistrementUser.php">
          <label for="username">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required class="form__input" />

          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Mot de passe" required class="form__input" />

          <label for="role">Rôle</label>
          <select name="role" id="role" required class="form__input">
            <option value="client">Client</option>
            <option value="artiste">Artiste</option>
          </select>

          <label for="profile_picture">Photo de profil</label>
          <input type="file" id="profile_picture" name="profile_picture" required class="form__input" />

          <label for="date_of_birth">Date de naissance</label>
          <input type="date" id="date_of_birth" name="date_of_birth" required class="form__input" />

          <div class="form__btn">
            <button type="submit" class="btn">S'inscrire</button>
          </div>
        </form>
        <div class="art-touch">L'art commence par une identité ✨</div>
      </div>
    </div>
  </section>

  <div id="notification"></div>

<!--=============== SCRIPT ===============-->
<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".formUser");
  const notification = document.getElementById("notification");

  function showNotification(message, isError = false) {
    notification.textContent = message;
    notification.style.backgroundColor = isError ? "#dc3545" : "#28a745";
    notification.style.display = "block";
    setTimeout(() => {
      notification.style.display = "none";
    }, 4000);
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(form);

    fetch("enregistrementUser.php", {
      method: "POST",
      body: formData,
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erreur lors de l'enregistrement");
      }
      return response.text();
    })
    .then((data) => {
      showNotification("Utilisateur enregistré avec succès !");
      form.reset();
    })
    .catch((error) => {
      showNotification("Une erreur est survenue : " + error.message, true);
    });
  });
});
</script>
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
          <p class="footer__description"><span>Email:</span>Hbaieb.yousef@gmail.com/Malekneili66@gmail.com</p>

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
