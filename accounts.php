<?php
require_once "session.php";

$userID = get_user_id();
$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$role = $connected ? ($_SESSION["role"] ?? '') : '';

require_once "user.class.php";
$user = new User();
$currentUser = $user->getUserByID($userID);

if (!$currentUser) {
    die("Erreur : utilisateur introuvable.");
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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css" />
    <title>Ecommerce Website</title>
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
            <li class="nav__item"><a href="shop.php" class="nav__link ">Shop</a></li>
             <?php if ($connected) : ?>
    <li class="nav__item">
      <a href="accounts.php" class="nav__link active-link">My Account</a>
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

    <div class="create-account">
      <h2 class="section__title">Modifier mes informations</h2>
      <form action="modifierUser.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($userID) ?>">

        <label>Nom d'utilisateur :</label>
        <input type="text" class="form__input" name="username" value="<?= htmlspecialchars($currentUser->username) ?>" required>

        <label>Mot de passe :</label>
        <input type="password" class="form__input" name="password" value="<?= htmlspecialchars($currentUser->password) ?>" required>

        <label>Photo de profil :</label>
        <input type="file" class="form__input" name="profile_picture" accept="image/*">

        <div class="form__btn">
          <button type="submit" class="btn">Enregistrer les modifications</button>
        </div>
      </form>

      <form action="deconnexion.php" method="POST" class="form__btn">
        <button type="submit" class="btn" >Se déconnecter</button>
      </form>

      <div class="art-touch">Votre profil est entre de bonnes mains 👤</div>
    </div>
  </body>
</html>
