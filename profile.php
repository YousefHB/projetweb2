<?php
require_once('art.class.php');
require_once('user.class.php');
require_once "session.php";



$user = new user();
$idUser = $user->getConnectedUserID();

if ($idUser !== null) {
    $userData = $user->getUserByID($idUser);

    if ($userData && $userData->role === 'artiste') {
        $art = new art();
        $oeuvres = $art->getByArtist($idUser);

        echo "<h2>🖼️ Vos œuvres :</h2>";
        if (empty($oeuvres)) {
            echo "<p>Vous n'avez encore rien publié.</p>";
        } else {
            foreach ($oeuvres as $o) {
                echo "<div style='border:1px solid #ccc; padding:1rem; margin-bottom:1rem; border-radius:8px;'>";
                echo "<h3>" . htmlspecialchars($o->title) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($o->description)) . "</p>";
                echo "<p><strong>Catégorie :</strong> " . htmlspecialchars($o->category) . "</p>";
                echo "<p><strong>Prix :</strong> " . htmlspecialchars($o->price) . " TND</p>";
                if (!empty($o->img_art)) {
                    echo "<img src='uploads/" . htmlspecialchars($o->img_art) . "' alt='image' style='max-width:100%; height:auto; border-radius:5px;' />";
                }
                echo "</div>";
            }
        }
    } else {
        echo "<p>Vous n’êtes pas un artiste ou votre compte est incorrectement configuré.</p>";
    }
} else {
    echo "<p>Veuillez vous connecter pour voir vos œuvres.</p>";
}
?>
