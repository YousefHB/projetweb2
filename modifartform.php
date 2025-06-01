<?php
require_once "session.php";
require_once "art.class.php";

if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== "1") {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "artiste") {
    die("Accès refusé : cette page est réservée aux artistes.");
}

$artID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$artObj = new Art();
$art = $artObj->getArtByID($artID);
if (!$art || $art->created_by != get_user_id()) {


    die("Erreur : publication introuvable ou accès non autorisé.");
}
?>
<?php
require_once "session.php"; // session_start() est déjà dans ce fichier

$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$username = $connected ? htmlspecialchars($_SESSION["username"] ?? 'Utilisateur') : '';
$role = $connected ? ($_SESSION["role"] ?? '') : '';
?>
<?php if (isset($_SESSION['notif_success'])) : ?>
  <div id="notification"><?= htmlspecialchars($_SESSION['notif_success']) ?></div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const notif = document.getElementById("notification");
      if (notif) {
        notif.style.display = "block";
        setTimeout(() => {
          notif.style.display = "none";
        }, 5000);
      }
    });
  </script>
  <?php unset($_SESSION['notif_success']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier la publication</title>
  <link rel="stylesheet" href="assets/css/styles.css">
   <style>
      #notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #28a745;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        font-weight: bold;
        animation: fadeInOut 5s forwards;
      }

      @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(20px); }
        10% { opacity: 1; transform: translateY(0); }
        90% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(20px); }
      }

      .create-account {
        max-width: 400px;
        margin: auto;
      }

      form {
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
        background-color: #007bff;
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
      }

      .btn:hover {
        background-color: #0056b3;
      }

      .section__title {
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
      .form-container {
  max-width: 500px;
  margin: 2rem auto;
  background-color: #ffffff;
  padding: 2rem 2.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.form-container h2 {
  text-align: center;
  margin-bottom: 2rem;
  font-weight: 700;
  color: #222;
}

form label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #333;
}

.form__input,
textarea,
input[type="file"] {
  width: 100%;
  padding: 0.7rem 1rem;
  font-size: 1rem;
  border: 1.8px solid #ccc;
  border-radius: 8px;
  transition: border-color 0.3s ease;
  box-sizing: border-box;
  font-family: inherit;
  margin-bottom: 1.5rem;
  resize: vertical;
}

.form__input:focus,
textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 5px #007bffaa;
}

button[type="submit"] {
  width: 100%;
  background-color: #007bff;
  color: white;
  padding: 0.9rem;
  font-size: 1.1rem;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.25s ease;
  box-shadow: 0 3px 8px rgba(0, 123, 255, 0.5);
}

button[type="submit"]:hover {
  background-color: #0056b3;
}

.old-image-container {
  text-align: center;
  margin-bottom: 1rem;
}

.old-image-container img {
  max-width: 100%;
  max-height: 250px;
  border-radius: 10px;
  object-fit: contain;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  border: 1.5px solid #ddd;
}

.old-image-label {
  font-style: italic;
  color: #666;
  margin-bottom: 0.5rem;
  display: block;
  font-size: 0.9rem;
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

  <?php if ($connected): ?>
    <li class="nav__item"><a href="accounts.php" class="nav__link ">My Account</a></li>
    <?php if ($role === "artiste"): ?>
      <li class="nav__item"><a href="compare.php" class="nav__link">Publication</a></li>
    <?php endif; ?>
  <?php else: ?>
    <li class="nav__item"><a href="login.php" class="nav__link">Se connecter</a></li>
    <li class="nav__item"><a href="login-register.php" class="nav__link">S'inscrire</a></li>
  <?php endif; ?>

  <?php if ($connected && $username === 'root'): ?>
    <li class="nav__item"><a href="admin.php" class="nav__link ">Administration</a></li>
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

<div class="form-container">
  <h2>Modifier votre publication</h2>
<form action="modifart.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id_art" value="<?= htmlspecialchars($art->ID_artwork) ?>">

  <label for="titre">Titre :</label>
  <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($art->title ?? '') ?>" required>

  <label for="description">Description :</label>
  <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($art->description ?? '') ?></textarea>

  <label for="prix">Prix (en $) :</label>
  <input type="text" id="prix" name="prix" value="<?= htmlspecialchars($art->price ?? '') ?>" required>




  <label for="image">Changer l’image (optionnel) :</label>
  <input type="file" id="image" name="image" accept="image/*">

  <button type="submit">Enregistrer les modifications</button>
</form>

</div>

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
