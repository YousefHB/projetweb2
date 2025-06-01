<?php
require_once "user.class.php";

// Vérification que le formulaire a bien été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Vérifie que tous les champs nécessaires existent
    if (
        isset($_POST['username'], $_POST['password'], $_POST['role'], $_POST['date_of_birth']) &&
        isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0
    ) {
        $us = new user();

        // Assignation des valeurs après nettoyage
        $us->username = htmlspecialchars(trim($_POST['username']));
        $us->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $us->role = htmlspecialchars(trim($_POST['role']));
        $us->date_of_birth = $_POST['date_of_birth']; // à valider si nécessaire

        // Traitement de l'image de profil
        $imageName = basename($_FILES['profile_picture']['name']);
        $fichierTemp = $_FILES['profile_picture']['tmp_name'];
        $uploadDir = 'images/';
        $uploadPath = $uploadDir . $imageName;

        // Vérifie si le dossier existe, sinon le créer
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($fichierTemp, $uploadPath)) {
            $us->profile_picture = $imageName;

            // Insertion dans la base de données
            if ($us->insertUser()) {
                echo "Utilisateur ajouté avec succès.";
            } else {
                echo "Erreur lors de l'insertion de l'utilisateur.";
            }
        } else {
            echo "Erreur lors du téléchargement de la photo de profil.";
        }

    } else {
        echo "Veuillez remplir tous les champs et ajouter une photo.";
    }

} else {
    echo "Méthode de requête invalide.";
}
exit();
?>
