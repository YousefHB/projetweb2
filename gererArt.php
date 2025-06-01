<?php
session_start();
require_once 'art.class.php';
require_once 'session.php';  // si tu as une gestion session dans ce fichier
require_once 'user.class.php';

if (!isset($_SESSION['id_user'])) {
    // Utilisateur non connecté, on affiche un message simple (ou redirige)
    $errorMsg = "Utilisateur non connecté.";
} else {
    $userId = $_SESSION['id_user'];

    $art = new art();
    $mes_arts = $art->getArtsByUserId($userId);

    if (empty($mes_arts)) {
        $infoMsg = "Aucune œuvre trouvée.";
    }
}


$cnx = new connexion();
$pdo = $cnx->CNXbase();



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
   <?php if ($connected): ?>
      <div style="background-color: #f1f1f1; padding: 10px; text-align: center; font-family: sans-serif;">
        Bonjour  <strong><?= $username ?></strong> !
        <a href="deconnexion.php" style="margin-left: 1rem;">Déconnexion</a>
      </div>
    <?php endif; ?>
<head>
    <meta charset="UTF-8" />
    <title>Mes œuvres - Mon Site</title>
    <link rel="stylesheet" href="assets/css/styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0 70px 0 70px; ;
            padding: 0;
        }
        h2 {
            color: #333;
        }
        .art-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .art-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 1rem;
            width: 250px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .art-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .art-item img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 0.7rem;
        }
        .art-item h3 {
            margin: 0.3rem 0;
            color: #222;
            font-size: 1em;
        }
        .art-item p {
            font-size: 0.9rem;
            color: #555;
            margin: 0.2rem 0;
        }
        .message {
            padding: 1rem;
            background: #ffe;
            border: 1px solid #cc9;
            border-radius: 8px;
            color: #996600;
            max-width: 600px;
            margin: 2rem auto;
            text-align: center;
        }
        .art-item p {
    margin-top: 10px;
}

/* Conteneur des boutons */
.art-item p a {
    display: inline-block;
    padding: 8px 15px;
    margin-right: 10px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

/* Bouton Modifier : bleu clair */
.btn-modifier {
    background-color:hsl(176, 88%, 27%); /* vert */
    color: white;
}

.btn-modifier:hover {
    background-color: white;
        color: hsl(176, 88%, 27%);;

}

/* Bouton Supprimer : rouge */

.art-item {
    display: flex;
    flex-direction: column;
    /* fixer une hauteur minimum ou hauteur fixe si tu veux un alignement uniforme global */
    min-height: 350px; /* Ajuste cette valeur selon la taille souhaitée */
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 8px;
    box-sizing: border-box;
}

.art-item p:last-child {
    margin-top: auto; /* pousse le dernier <p> (les boutons) en bas */
}

/* Style boutons côte à côte */
.art-item p:last-child a {
    display: inline-block;
    padding: 8px 15px;
    margin-right: 10px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

/* Bouton Modifier */



/* Bouton Supprimer */
.btn-supprimer {
    background-color: #FFAAAA; /* couleur rose/violet */
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-supprimer:hover {
    background-color: white;
    color: #FFAAAA;
    border: 1px solid #FFAAAA;
}

  strong {
    color: #FFAAAA;
  }




    </style>
</head>
<body>

<?php if (!empty($errorMsg)) : ?>
    <div class="message"><?= htmlspecialchars($errorMsg) ?></div>
<?php else: ?>
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
    <?php if (!empty($infoMsg)) : ?>
        <div class="message"><?= htmlspecialchars($infoMsg) ?></div>
    <?php else: ?>
        <div class="art-container">
            <?php foreach ($mes_arts as $a) : ?>
                <div class="art-item">
                    <h3><?= htmlspecialchars($a['title']) ?></h3>
                    <img src="imagesart/<?= htmlspecialchars($a['img_art']) ?>" alt="<?= htmlspecialchars($a['title']) ?>" />
                    <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($a['description'])) ?></p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($a['price']) ?> DT</p>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($a['category']) ?></p>
                     <!-- Boutons Modifier et Supprimer -->
          <p>
  <a href="modifartform.php?id=<?= urlencode($a['ID_artwork']) ?>" class="btn btn-modifier">Modifier</a>
  <a href="deleteart.php?id=<?= urlencode($a['ID_artwork']) ?>" 
     class="btn btn-supprimer" 
     onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette œuvre ?');">
     Supprimer
  </a>
</p>


                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
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
