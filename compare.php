<?php
require_once "session.php"; // Démarre la session et permet l’accès aux fonctions

// On vérifie que l’utilisateur est connecté, sinon redirigé dans verifier_session()
verifier_session();

// Optionnel : récupérer le rôle ou ID utilisateur pour personnaliser la page si besoin
$connected = true;  // puisque verifier_session a validé la connexion
$role = get_role();
$userId = get_user_id();
?>
<?php
require_once "session.php"; // session_start() est déjà dans ce fichier

$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$username = $connected ? htmlspecialchars($_SESSION["username"] ?? 'Utilisateur') : '';
$role = $connected ? ($_SESSION["role"] ?? '') : '';
?>

<!DOCTYPE html>
<html lang="en">
   <?php if ($connected): ?>
      <div style="background-color: #f1f1f1; padding: 10px; text-align: center; font-family: sans-serif;">
        Bonjour notre artiste <strong><?= $username ?></strong> !
        <a href="deconnexion.php" style="margin-left: 1rem;">Déconnexion</a>
      </div>
    <?php endif; ?>
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
    <link rel="stylesheet" href="./assets/css/styles.css" />

    <title>Ecommerce Website</title>
  </head>
  <style>
    /* Style général du formulaire */
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
.login-register__container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh; /* Prend toute la hauteur de l'écran */
}


   </style>
    <script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".form");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Empêche la redirection
    alert("Ton œuvre a été soumise avec succès !");

    // Si tu veux VRAIMENT envoyer les données sans redirection :
    const formData = new FormData(form);

    fetch("enregistrementArt.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (response.ok) {
          return response.text(); // ou response.json() si tu retournes du JSON
        } else {
          throw new Error("Erreur lors de l'envoi du formulaire");
        }
      })
      .then((data) => {
        console.log(data); // Tu peux l'utiliser pour montrer un message personnalisé
        alert("Publication enregistrée !");
        form.reset(); // Pour réinitialiser le formulaire
      })
      .catch((error) => {
        alert("Une erreur est survenue : " + error.message);
      });
  });
});
</script>

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
            <li class="nav__item">
              <a href="accounts.php" class="nav__link">My Account</a>
            </li>
                 <?php if ($connected && $role === "artiste") : ?>
  <li class="nav__item">
    <a href="compare.php" class="nav__link  active-link">Publication</a>
  </li>
  <?php endif; ?>
     <li class="nav__item">
              <a href="login.php" class="nav__link">Se connecter</a>
            </li>
            <li class="nav__item">
              <a href="login-register.php" class="nav__link "
                >S'inscrire</a
              >
            </li>
          
          </ul>
         
        </div>
        <div class="header__user-actions">
          <a href="wishlist.php" class="header__action-btn" title="Wishlist">
            <img src="assets/img/icon-heart.svg" alt="" />
            <span class="count">3</span>
          </a>
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

<!--=============== publieer art ===============-->
<section class="login-register section--lg">
  <div class="login-register__container container grid">
    <div class="create-account">
      <h3 class="section__title">Publier un artwork</h3>
      <form class="form grid" action="enregistrementArt.php" method="post" enctype="multipart/form-data">
        
        <label for="titre">Titre de l'œuvre</label>
        <input type="text" name="title" placeholder="Titre de l'œuvre" required class="form__input">

        <label for="description">Description</label>
        <textarea name="description" placeholder="Décris ton œuvre..." required class="form__input" rows="5"></textarea>

          <label for="price">Prix</label>
        <textarea name="price" placeholder="prix" required class="form__input" rows="5"></textarea>


        <label for="categorie">Catégorie</label>
        <select name="category" id="categorie" required class="form__input">
          <option value="">-- Sélectionner une catégorie --</option>
          <option value="logo">Logo</option>
          <option value="montage_video">Montage vidéo</option>
          <option value="peinture">Peinture</option>
          <option value="made_by_hand">Fait main</option>
          <option value="illustration">Illustration</option>
          <option value="animation">Animation</option>
          <option value="3d_modeling">Modélisation 3D</option>
          <option value="photographie">Photographie</option>
          <option value="design_graphique">Design graphique</option>
                    <option value="Anime">Anime</option>
                                        <option value="Enfant">Enfant</option>

                                        <option value="Livre">Livre</option>



          <option value="art_abstrait">Art abstrait</option>
          <option value="autre">Autre...</option>
        </select>

        <label for="image">Image de l'œuvre</label>
        <input type="file" name="img_art" required class="form__input">

        <div class="form__btn">
          <button type="submit" class="btn">Publier</button>
        </div>
      </form>
      <div class="art-touch">Exprime ton art librement 🎨</div>
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
        <div class="footer__content">
          <h3 class="footer__title">Address</h3>
          <ul class="footer__links">
            <li><a href="#" class="footer__link">About Us</a></li>
            <li><a href="#" class="footer__link">Delivery Information</a></li>
            <li><a href="#" class="footer__link">Privacy Policy</a></li>
            <li><a href="#" class="footer__link">Terms & Conditions</a></li>
            <li><a href="#" class="footer__link">Contact Us</a></li>
            <li><a href="#" class="footer__link">Support Center</a></li>
          </ul>
        </div>
        <div class="footer__content">
          <h3 class="footer__title">My Account</h3>
          <ul class="footer__links">
            <li><a href="#" class="footer__link">Sign In</a></li>
            <li><a href="#" class="footer__link">View Cart</a></li>
            <li><a href="#" class="footer__link">My Wishlist</a></li>
            <li><a href="#" class="footer__link">Track My Order</a></li>
            <li><a href="#" class="footer__link">Help</a></li>
            <li><a href="#" class="footer__link">Order</a></li>
          </ul>
        </div>
        <div class="footer__content">
          <h3 class="footer__title">Secured Payed Gateways</h3>
          <img
            src="./assets/img/payment-method.png"
            alt=""
            class="payment__img"
          />
        </div>
      </div>
      <div class="footer__bottom">
        <p class="copyright">&copy; 2024 Evara. All right reserved</p>
        <span class="designer">Designer by Crypticalcoder</span>
      </div>
    </footer>

    <!--=============== SWIPER JS ===============-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
  </body>
</html>
