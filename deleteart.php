<?php
require_once('art.class.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $art = new art();
    $art->deleteArt($_GET['id']);
}

header('Location: gererArt.php');
exit;
?>
