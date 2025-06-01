<?php
require_once "session.php"; // session_start() est déjà dans ce fichier

// Optionnel : récupérer le rôle si connecté
$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$role = $connected ? ($_SESSION["role"] ?? '') : '';
?>
<?php
require_once "session.php";  // inclut session_start() et les fonctions

$message = "";

if (isset($_POST['submit_login'])) {
    require_once "user.class.php";
    $us = new user();

    $us->username = $_POST["login"];
    $us->password = $_POST["pwd"];

    try {
        $res = $us->getUser();
        $data = $res->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            if (password_verify($us->password, $data['password'])) {
                // Connexion réussie -> on remplit les variables de session
                $_SESSION["connecte"] = "1";
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];
                $_SESSION["id_user"] = $data["ID"];            // <- ici tu récupères l'ID réel depuis la BDD

                header("Location: index.php");  // redirige vers page d'accueil ou autre
                exit;
            } else {
                $message = "❌ Mot de passe incorrect.";
            }
        } else {
            $message = "❌ Aucun utilisateur trouvé.";
        }
    } catch (PDOException $e) {
        $message = "🚨 ERREUR : " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/styles.css" />

    <title>Login - Ecommerce Website</title>
  </head>
  <body>
    <!--=============== HEADER ===============-->
    <header class="header">
      

      <nav class="nav container">
        <a href="index.php" class="nav__logo">
          <img
            class="nav__logo-img"
                        src="assets/img/logoart.png"

            alt="website logo"
          />
        </a>
        <div class="nav__menu" id="nav-menu">
          <ul class="nav__list">
            <li class="nav__item">
              <a href="index.php" class="nav__link">Home</a>
            </li>
            <li class="nav__item">
              <a href="shop.php" class="nav__link">Shop</a>
            </li>


             <?php if ($connected) : ?>
    <li class="nav__item">
      <a href="accounts.php" class="nav__link">My Account</a>
    </li>
  <?php endif; ?>
           
                <?php if ($connected && $role === "artiste") : ?>
  <li class="nav__item">
    <a href="compare.php" class="nav__link">Publication</a>
  </li>
  <?php endif; ?>
           
              <li class="nav__item">
              <a href="login.php" class="nav__link active-link">Se connecter</a>
            </li>
             <li class="nav__item">
              <a href="login-register.php" class="nav__link">s'inscrire</a>
            </li>
          </ul>
          <div class="header__search">
            <input
              type="text"
              placeholder="Search For Items..."
              class="form__input"
            />
            <button class="search__btn">
              <img src="assets/img/search.png" alt="search icon" />
            </button>
          </div>
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
          <li><span class="breadcrumb__link">Login</span></li>
        </ul>
      </section>

      <!--=============== LOGIN FORM ===============-->
      <section class="login-register section--lg">
        <div class="login-register__container container grid">
          <div class="login">
            <h3 class="section__title">Se connecter</h3>
            <form action="login.php" method="post" class="form grid">
              <label for="login">Nom d'utilisateur</label>
              <input
                type="text"
                name="login"
                id="login"
                placeholder="Nom d'utilisateur"
                class="form__input"
                required
              />

              <label for="pwd">Mot de Passe</label>
              <input
                type="password"
                name="pwd"
                id="pwd"
                placeholder="Votre mot de passe"
                class="form__input"
                required
              />

              <div class="form__btn">
                <button type="submit" class="btn" name="submit_login">Se connecter</button>

              </div>
            </form>
            <?php if (!empty($message)) : ?>
  <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

 
          </div>
        </div>
      </section>

      <!--=============== NEWSLETTER ===============-->
      <section class="newsletter section">
        <div class="newsletter__container container grid">
          <h3 class="newsletter__title flex">
            <img
              src="./assets/img/icon-email.svg"
              alt=""
              class="newsletter__icon"
            />
            Sign in to Newsletter
          </h3>
          <p class="newsletter__description">
            ...and receive $25 coupon for first shopping.
          </p>
          <form action="" class="newsletter__form">
            <input
              type="text"
              placeholder="Enter Your Email"
              class="newsletter__input"
            />
            <button type="submit" class="newsletter__btn">Subscribe</button>
          </form>
        </div>
      </section>
    </main>

    <!--=============== FOOTER ===============-->
    <footer class="footer container">
      <div class="footer__container grid">
        <div class="footer__content">
          <a href="index.php" class="footer__logo">
            <img src="./assets/img/logo.svg" alt="" class="footer__logo-img" />
          </a>
          <h4 class="footer__subtitle">Contact</h4>
          <p class="footer__description">
            <span>Address:</span> 13 Tlemcen Road, Street 32, Beb-Wahren
          </p>
          <p class="footer__description">
            <span>Phone:</span> +01 2222 365 /(+91) 01 2345 6789
          </p>
          <p class="footer__description">
            <span>Hours:</span> 10:00 - 18:00, Mon - Sat
          </p>
          <div class="footer__social">
            <h4 class="footer__subtitle">Follow Me</h4>
            <div class="footer__links flex">
              <a href="#">
                <img
                  src="./assets/img/icon-facebook.svg"
                  alt=""
                  class="footer__social-icon"
                />
              </a>
              <a href="#">
                <img
                  src="./assets/img/icon-twitter.svg"
                  alt=""
                  class="footer__social-icon"
                />
              </a>
              <a href="#">
                <img
                  src="./assets/img/icon-instagram.svg"
                  alt=""
                  class="footer__social-icon"
                />
              </a>
              <a href="#">
                <img
                  src="./assets/img/icon-pinterest.svg"
                  alt=""
                  class="footer__social-icon"
                />
              </a>
              <a href="#">
                <img
                  src="./assets/img/icon-youtube.svg"
                  alt=""
                  class="footer__social-icon"
                />
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!--=============== SWIPER JS ===============-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
  </body>
</html>
