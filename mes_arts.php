<?php
session_start();
require_once('art.class.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    echo "Utilisateur non connecté.";
    exit;
}

$userId = $_SESSION['id_user'];
$art = new art();
$mes_arts = $art->getArtsByUserId($userId);

include 'mes_arts_view.php';
?>
