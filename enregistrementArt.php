<?php
require_once "session.php";
verifier_session();

require_once "art.class.php";

$art = new art();

// Récupérer l'ID de l'utilisateur connecté depuis la session
$userId = get_user_id();
if ($userId === null) {
    die("Utilisateur non connecté ou ID non défini.");
}
$art->created_by = $userId;

// Récupérer les données du formulaire
$art->title = $_POST['title'];
$art->description = $_POST['description'];
$art->price = $_POST['price'];
$art->category = $_POST['category'];

$art->img_art = $_FILES['img_art']['name'];
$fichierTemp = $_FILES['img_art']['tmp_name'];
move_uploaded_file($fichierTemp, 'imagesart/' . $art->img_art);


// Insertion dans la base
$art->insertArt();

echo "Œuvre ajoutée avec succès.";
exit;
?>
