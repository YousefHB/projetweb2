<?php
require_once "session.php";

if (!is_connected()) {
    header("Location: login.php");
    exit;
}

require_once "user.class.php";
$user = new User();

// 💡 On récupère l'ID depuis la session
$id = get_user_id();

if (!$id) {
    die("Erreur : utilisateur non identifié.");
}

// 📝 Mise à jour des données
$user->username = $_POST['username'];
$user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$photo = $_FILES['profile_picture']['name'];

if (empty($photo)) {
    $user->modifierUserSansPhoto($id);
} else {
    $temp = $_FILES['profile_picture']['tmp_name'];
    move_uploaded_file($temp, 'images/' . $photo);
    $user->profile_picture = $photo;
    $user->modifierUser($id);
}
header("Location: accounts.php");


session_start();
// ... traitement de modification réussi
$_SESSION['notif_success'] = "Informations mises à jour avec succès.";
header("Location: accounts.php");
exit;

