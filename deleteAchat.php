<?php
require_once('achat.class.php');

if (isset($_GET['id_artwork']) && isset($_GET['id_user'])) {
    $achat = new achat();
    $achat->deleteAchat($_GET['id_artwork'], $_GET['id_user']);
}

header('Location: cart.php');
exit;
