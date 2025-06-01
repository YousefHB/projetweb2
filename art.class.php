<?php
require_once('pdo.php');
require_once('user.class.php');

class art
{
    public $ID_artwork;
    public $created_by;
    public $title;
    public $description;
    public $img_art;
    public $price;
    public $category;
    public $approved = 0;

    // Récupérer tous les arts créés par un utilisateur donné
    public function getArtsByUserId($userId) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $sql = "SELECT * FROM art WHERE created_by = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insérer une nouvelle œuvre d'art dans la base
    public function insertArt() {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $req = "INSERT INTO art (created_by, title, description, img_art, price, category, approved)
                VALUES (:created_by, :title, :description, :img_art, :price, :category, :approved)";
        $stmt = $pdo->prepare($req);

        $stmt->execute([
            ':created_by' => $this->created_by,
            ':title' => $this->title,
            ':description' => $this->description,
            ':img_art' => $this->img_art,
            ':price' => $this->price,
            ':category' => $this->category,
            ':approved' => $this->approved
        ]);

        // Gestion des erreurs
        $error = $stmt->errorInfo();
        if ($error[0] != "00000") {
            print_r($error);
        }
    }

    // Récupérer les œuvres d'art d'une certaine catégorie
    /*public function getByCategory() {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        // Assurez-vous que category est bien une chaîne
        $category = is_array($this->category) ? '' : $this->category;

        $stmt = $pdo->prepare("SELECT * FROM art WHERE category = :category");
        $stmt->execute(['category' => $category]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }*/
public function getByCategory()
{
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $sql = "SELECT * FROM art WHERE category = :cat AND approved = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cat' => $this->category]);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}




    // Récupérer la liste des catégories distinctes
    public function getCategories() {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->query("SELECT DISTINCT category FROM art ORDER BY category ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtenir le nom d'utilisateur à partir de son ID
    public function getNomCreateur($id_user) {
        $user = new user();
        $createur = $user->getUserByID($id_user);
        return $createur ? $createur->username : "Inconnu";
    }

    //suuprimer une œuvre d'art
public function deleteArt($id_artwork) {
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $req = "DELETE FROM art WHERE ID_artwork = :id_artwork";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':id_artwork', $id_artwork, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
} 
public function getArtByID($id)
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $stmt = $pdo->prepare("SELECT * FROM art WHERE ID_artwork = :id");
    $stmt->execute(['id' => $id]);
    $art = $stmt->fetch(PDO::FETCH_OBJ);

    return $art ?: null;
}



public function modifierArtAvecImage($id, $titre, $description, $prix, $image) {
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $sql = "UPDATE art SET 
                title = :titre, 
                description = :description, 
                price = :prix, 
                img_art = :image 
            WHERE ID_artwork = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        throw new Exception("Erreur lors de la mise à jour avec image.");
    }
}

public function modifierArtSansImage($id, $titre, $description, $prix) {
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $sql = "UPDATE art SET 
                title = :titre, 
                description = :description, 
                price = :prix 
            WHERE ID_artwork = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        throw new Exception("Erreur lors de la mise à jour sans image.");
    }
}
public function getnotapproverArt() {
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $stmt = $pdo->query("SELECT * FROM art WHERE approved = 0");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
public function approveArt($id_artwork) {
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $req = "UPDATE art SET approved = 1 WHERE ID_artwork = :id_artwork";
    $stmt = $pdo->prepare($req);
    $stmt->bindParam(':id_artwork', $id_artwork, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



// <-- ici pas de point-virgule
} // fermeture de la classe


?>
