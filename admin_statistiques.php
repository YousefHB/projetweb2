<?php
require_once("pdo.php"); // ta connexion PDO ici
$cnx = new connexion();
$pdo = $cnx->CNXbase();

// Nombre de clients
$stmt = $pdo->query("SELECT COUNT(*) AS nb_clients FROM user WHERE role = 'client'");
$nb_clients = $stmt->fetch()['nb_clients'];

// Nombre d'artistes
$stmt = $pdo->query("SELECT COUNT(*) AS nb_artistes FROM user WHERE role = 'artiste'");
$nb_artistes = $stmt->fetch()['nb_artistes'];

// Nombre de publications faites par les artistes
$stmt = $pdo->query("
  SELECT COUNT(*) AS nb_publications
  FROM art a
  JOIN user u ON a.created_by = u.ID
  WHERE u.role = 'artiste'
");
$nb_publications = $stmt->fetch()['nb_publications'];


// Nombre total d’articles vendus et le montant total des ventes
$stmt = $pdo->query("
  SELECT 
    count(*) AS total_articles_vendus, 
    SUM(total) AS total_ventes
  FROM commande
");
$result = $stmt->fetch();
$total_articles_vendus = $result['total_articles_vendus'] ?? 0;
$total_ventes = $result['total_ventes'] ?? 0;


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
  <meta charset="UTF-8">
  <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/css/styles.css" />
    <title>Statistique</title>
  <style>
    body { font-family: Arial;  background: #FFFFFFFF; }
    .card { background: white;  border-radius: 10px; box-shadow: 0 0 10px #ddd; }
    h1 { color: #333; }
    <style>
  /* Reset minimal */
  * {
    box-sizing: border-box;
  }



  /* Header reste normal */


  /* Main container prend tout l’espace restant */
  main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center; /* centre verticalement */
    align-items: center;     /* centre horizontalement */
    padding: 20px;
    max-width: 900px;
    margin: 0 auto;
  }

  h1 {
    color: #1A7A68

;
    font-weight: 700;
    margin-bottom: 30px;
    text-align: center;
  }

  .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
    padding: 30px 40px;
    margin: 15px;
    width: 100%;
    max-width: 400px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgb(0 0 0 / 0.25);
  }

  .card h2 {
    margin-top: 0;
    color: #E73CB9FF;
    font-weight: 600;
    margin-bottom: 20px;
    font-size: 1.6rem;
  }

  .card p {
    font-size: 1rem;
    line-height: 1.5;
    color: #444;
  }

  strong {
    color: #FFAAAA;
  }

  /* Responsive : sur mobile, cartes en colonne */
  @media (max-width: 600px) {
    main {
      padding: 10px;
    }
    .card {
      max-width: 100%;
      padding: 20px;
      margin: 10px 0;
    }
  }
</style>

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
    <li class="nav__item"><a href="admin.php" class="nav__link">Administration</a></li>
    <li class="nav__item"><a href="admin_statistiques.php" class="nav__link active-link">Statistique</a></li>
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

 <main>
  <h1>📊 Statistiques</h1>

  <div class="card">
    <h2>Utilisateurs</h2>
    <p>👤 Clients inscrits : <strong><?= $nb_clients ?></strong></p>
    <p>🎨 Artistes inscrits : <strong><?= $nb_artistes ?></strong></p>
  </div>

  <div class="card">
    <h2>Publications</h2>
    <p>🖼️ Publications publiées par des artistes : <strong><?= 1+$nb_publications ?></strong></p>
  </div>
  <div class="card">
  <h2>Ventes</h2>
  <p>🛒 Nombre total des commandes : <strong><?= $total_articles_vendus ?></strong></p>
  <p>💰 Montant total des ventes : <strong><?= number_format($total_ventes, 2, ',', ' ') ?> TND</strong></p>
</div>

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
