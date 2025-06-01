<?php
require_once "session.php";
require_once "art.class.php";

if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== "1") {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] !== "artiste") {
    die("Accès refusé.");
}

$id_art = intval($_POST['id_art']);
$titre = trim($_POST['titre']);
$description = trim($_POST['description']);
$prix = trim($_POST['prix']);

$artObj = new Art();

// Récupérer l'art à modifier
$art = $artObj->getArtByID($id_art);

// Vérifier que l'utilisateur est bien propriétaire de cet art
if (!$art || $art->created_by != get_user_id()) {
    die("Erreur : accès non autorisé.");
}

if (!empty($_FILES['image']['name'])) {
    $uploadDir = 'imagesart/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Renommer le fichier pour éviter les collisions (ex: timestamp + nom d'origine)
    $originalName = basename($_FILES['image']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFileName = 'art_' . $id_art . '_' . time() . '.' . $extension;
    $imagePath = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // Mise à jour avec nouvelle image (seulement le nom de fichier)
        $artObj->modifierArtAvecImage($id_art, $titre, $description, $prix, $newFileName);
    } else {
        die("Erreur lors de l'upload de l'image.");
    }
} else {
    // Mise à jour sans changer l'image
    $artObj->modifierArtSansImage($id_art, $titre, $description, $prix);
}

header("Location: gererArt.php");
exit;
?>
