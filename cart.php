<?php
require_once 'session.php';
require_once 'pdo.php';
require_once 'art.class.php';
require_once 'user.class.php';
require_once 'achat.class.php';

$user_id = get_user_id();

if (!$user_id) {
    die("Erreur : ID utilisateur non trouvé dans la session.");
}

try {
    $achats = achat::getAchatsParUtilisateur($user_id);
} catch (Exception $e) {
    $error_message = "Erreur lors de la récupération des achats : " . $e->getMessage();
}

$cnx = new connexion();
$pdo = $cnx->CNXbase();

$art = new art($pdo);
$categories = $art->getCategories();

$connected = isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1";
$username = $connected ? htmlspecialchars($_SESSION["username"] ?? 'Utilisateur') : '';
$role = $connected ? ($_SESSION["role"] ?? '') : '';

$user = new User();
$currentUser = $connected ? $user->getUserByID($user_id) : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="./assets/css/styles.css">
  <title>Mes Achats - Ecommerce Website</title>
  <style>
    strong { color: #FFAAAA; }
    .notification.success { color: green; }
    .notification.error { color: red; }
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
      <img class="nav__logo-img" src="assets/img/logoart.png" alt="website logo">
    </a>
    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">
        <li class="nav__item"><a href="index.php" class="nav__link">Home</a></li>
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
        <img src="assets/img/icon-cart.svg" alt=""><span class="count">3</span>
      </a>
    </div>
  </nav>
</header>

<main class="main">
  <section class="breadcrumb">
    <ul class="breadcrumb__list flex container">
      <li><a href="index.php" class="breadcrumb__link">Home</a></li>
      <li><span class="breadcrumb__link">></span></li>
      <li><span class="breadcrumb__link">Mes Achats</span></li>
    </ul>
  </section>

  <section class="cart section--lg container">
    <div class="table__container">
      <?php if (isset($error_message)): ?>
        <p style="color:red;"><?= htmlspecialchars($error_message) ?></p>
      <?php elseif (empty($achats)): ?>
        <p>Aucun achat trouvé pour cet utilisateur.</p>
      <?php else: ?>
        <form method="post" action="passer_commande.php">
        <table class="table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Nom</th>
              <th>Prix</th>
              <th>Quantité</th>
              <th>Supprimer</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($achats as $achat): ?>
              <tr>
                <td><img src="imagesart/<?= htmlspecialchars($achat['img_art'] ?? 'default-product.jpg') ?>" alt="<?= htmlspecialchars($achat['title'] ?? 'Sans titre') ?>" class="table__img"></td>
                <td>
                  <h3 class="table__title"><?= htmlspecialchars($achat['title'] ?? 'Sans titre') ?></h3>
                  <p class="table__description"><?= htmlspecialchars($achat['description'] ?? '') ?></p>
                </td>
                <td><span class="table__price"><?= number_format((float)$achat['price'], 2) ?> DT</span></td>
    <td>
      <input
        type="number"
        name="quantite[<?= $achat['id_artwork'] ?>]"
        value="<?= intval($achat['quantite'] ?? 1) ?>"
        min="1"
      >
    </td>


                <td><a href="deleteAchat.php?id_artwork=<?= $achat['id_artwork']; ?>&id_user=<?= $achat['id_user']; ?>" onclick="return confirm('Supprimer cet article ?');">Supprimer</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="text-align: right; margin-top: 20px;">
          <button type="submit" class="btn btn-primary">Acheter</button>
        </div>
        </form>
        <div id="notification"></div>
      <?php endif; ?>
    </div>
  </section>

  <section class="newsletter section home__newsletter">
    <div class="newsletter__container container grid">
      <h3 class="newsletter__title flex">
        <img src="./assets/img/icon-email.svg" alt="" class="newsletter__icon">
        Inscrivez-vous à Artistry
      </h3>
      <p class="newsletter__description">...et recevez un coupon de 25DT pour votre premier achat.</p>
      <form class="newsletter__form">
        <input type="email" placeholder="Entrez votre e-mail" class="newsletter__input" required>
        <a href="login-register.php" class="newsletter__btn">S'inscrire</a>
      </form>
    </div>
  </section>
</main>

<footer class="footer container">
  <div class="footer__container grid">
    <div class="footer__content">
      <a href="index.php" class="footer__logo">
        <img src="assets/img/logoart.png" alt="" class="footer__logo-img">
      </a>
      <h4 class="footer__subtitle">Contact</h4>
      <p class="footer__description"><span>Adresse:</span> Sfax, Tunisie</p>
      <p class="footer__description"><span>Téléphone:</span> (+216) 51 267 554 / (+216) 24 129 525</p>
      <p class="footer__description"><span>Email:</span> Hbaieb.yousef@gmail.com</p>

      <div class="footer__social">
        <h4 class="footer__subtitle">Suivez-nous</h4>
        <div class="footer__links flex">
          <a href="#"><img src="./assets/img/icon-facebook.svg" class="footer__social-icon" alt="Facebook"></a>
          <a href="#"><img src="./assets/img/icon-twitter.svg" class="footer__social-icon" alt="Twitter"></a>
          <a href="#"><img src="./assets/img/icon-instagram.svg" class="footer__social-icon" alt="Instagram"></a>
          <a href="#"><img src="./assets/img/icon-pinterest.svg" class="footer__social-icon" alt="Pinterest"></a>
          <a href="#"><img src="./assets/img/icon-youtube.svg" class="footer__social-icon" alt="YouTube"></a>
        </div>
      </div>
    </div>
  </div>
</footer>



<?php if (isset($_GET['commande']) && $_GET['commande'] === 'success'): ?>
  <div id="notif-success" class="notification success">
    ✅ Commande passée avec succès !
  </div>
  <script>
    const notif = document.getElementById('notif-success');
    notif.style.display = 'block';
    setTimeout(() => {
      notif.style.display = 'none';
    }, 5000);
  </script>
<?php endif; ?>

<style>
  .notification.success {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #d4edda;
    color: #155724;
    padding: 15px 20px;
    border-left: 6px solid #28a745;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: sans-serif;
    z-index: 1000;
    display: none;
  }
</style>

</body>
</html>