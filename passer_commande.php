<?php
require_once 'pdo.php';
require_once 'session.php';

require_once 'user.class.php';
require_once 'art.class.php';
$user_id = get_user_id();

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

$cnx = new connexion();
$pdo = $cnx->CNXbase();

// Exécuter la commande principale
passerCommande($pdo, $user_id);

function passerCommande($pdo, $user_id) {
    // Récupérer les achats depuis la base
        var_dump($_POST['quantite']);

    $achats = recupererAchats($pdo, $user_id); // pour récupérer les achats de l'utilisateur

    // Récupérer les quantités envoyées par POST
    $quantites_post = $_POST['quantite'] ?? [];

    // Mettre à jour les quantités dans $achats avec celles du POST
    foreach ($achats as &$achat) {
        $id_artwork = $achat['id_artwork'];
        if (isset($quantites_post[$id_artwork]) && is_numeric($quantites_post[$id_artwork]) && $quantites_post[$id_artwork] > 0) {
            $achat['quantite'] = (int)$quantites_post[$id_artwork];
        }
    }
    unset($achat); // pour éviter référence persistante

    if (empty($achats)) {
        echo json_encode(['success' => false, 'message' => 'Aucun article à acheter']);
        exit;
    }

    $total = calculerTotal($achats);
    $id_commande = insererCommande($pdo, $user_id, $total);
    insererDetailsCommande($pdo, $id_commande, $achats);
    viderPanier($pdo, $user_id);

    // Redirection vers cart.php avec message de succès
    header("Location: cart.php?commande=success");
    exit;
}


function recupererAchats($pdo, $user_id) {     //pour récupérer les achats de l'utilisateur avec join sur art pour obtenir le prix
    $stmt = $pdo->prepare("
        SELECT a.*, art.price AS price 
        FROM achat a
        JOIN art ON a.id_artwork = art.id_artwork
        WHERE a.id_user = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculerTotal($achats) {
    $total = 0;
    foreach ($achats as $achat) {
        $total += $achat['price'] * $achat['quantite'];
    }
    return $total;
}

function insererCommande($pdo, $user_id, $total) {
    $stmt = $pdo->prepare("INSERT INTO commande (id_user, total, statut) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $total, 'en cours']);
    return $pdo->lastInsertId();
}

function insererDetailsCommande($pdo, $id_commande, $achats) {
    $stmtDetail = $pdo->prepare("
        INSERT INTO commande_detail (id_commande, id_artwork, quantite, prix_unitaire, sous_total) 
        VALUES (?, ?, ?, ?, ?)
    ");
    foreach ($achats as $achat) {
        $quantite = $achat['quantite'];
        $prix = $achat['price'];
        $sous_total = $quantite * $prix;
        $stmtDetail->execute([$id_commande, $achat['id_artwork'], $quantite, $prix, $sous_total]);
    }
}

function viderPanier($pdo, $user_id) {
    $stmt = $pdo->prepare("DELETE FROM achat WHERE id_user = ?");
    $stmt->execute([$user_id]);
}
