<?php
require_once('pdo.php');

class achat
{
    public $id_user;
    public $id_artwork;
    public $quantite;

    // Méthode pour insérer un achat
    public function ajouterAchat()
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->prepare("INSERT INTO achat (id_user, id_artwork, quantite) VALUES (?, ?, ?)");
        return $stmt->execute([$this->id_user, $this->id_artwork, $this->quantite]);
    }

    // Méthode pour récupérer les achats d’un utilisateur
    public static function getAchatsParUtilisateur($user_id)
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->prepare("
            SELECT a.*, art.title, art.description, art.img_art, art.price
            FROM achat a
            JOIN art ON a.id_artwork = art.ID_artwork
            WHERE a.id_user = :user_id
        ");

        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function deleteAchat($id_artwork, $id_user)
{
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $stmt = $pdo->prepare("DELETE FROM achat WHERE id_artwork = ? AND id_user = ?");
    return $stmt->execute([$id_artwork, $id_user]);
}

}
?>
