<?php
session_start();
require_once('achat.class.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_art = intval($_GET['id']);

    $achat = new Achat();
    $achat->id_user = $_SESSION['id_user'];
    $achat->id_artwork = $id_art;
    $achat->quantite = 1;

    if ($achat->ajouterAchat()) {
        header("Location: shop.php?success=1");
        exit();
    } else {
        echo "Erreur lors de l'ajout à la table achat.";
    }
} else {
    echo "ID de l'œuvre manquant.";
}
?>
