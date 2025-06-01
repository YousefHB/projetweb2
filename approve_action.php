<?php
require_once 'session.php';
require_once 'pdo.php';
require_once 'art.class.php';

if (!isset($_POST['id_artwork'], $_POST['action'])) {
    header('Location: admin.php'); // ou autre page
    exit;
}

$id_artwork = (int)$_POST['id_artwork'];
$action = $_POST['action'];

$cnx = new connexion();
$pdo = $cnx->CNXbase();
$art = new art($pdo);

if ($action === 'approve') {
    $success = $art->approveArt($id_artwork);
} elseif ($action === 'reject') {
    // Ici tu peux décider quoi faire si refuse (ex : supprimer ou marquer d'une autre façon)
    // Par exemple, supprimer l'artwork:
    $stmt = $pdo->prepare("DELETE FROM art WHERE ID_artwork = :id_artwork");
    $success = $stmt->execute([':id_artwork' => $id_artwork]);
} else {
    $success = false;
}

if ($success) {
    header('Location: admin.php?msg=success');
} else {
    header('Location: admin.php?msg=error');
}
exit;
