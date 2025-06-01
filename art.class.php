<?php        
require_once('pdo.php');
require_once('user.class.php');
// Classe art pour gérer les œuvres d'art
class art
{
/* attributs de la classe utilisateur*/
	
public $ID_artwork;
public $created_by;
public $title;
public $description;
public $img_art;
public $price;
public $category;
public $approved = 0;

/* constructeur de la classe */


public  function insertArt()
 {
require_once('pdo.php');
$cnx=new connexion();
$pdo=$cnx->CNXbase();
$req="INSERT INTO art (created_by,title,description,img_art,price,category,approved)
VALUES ('$this->created_by','$this->title','$this->description','$this->img_art','$this->price','$this->category','$this->approved')";
$pdo->exec($req);
$error = $pdo->errorInfo();
if ($error[0] != "00000") {
    print_r($error);
}
}


 public function getByCategory()
    {
        require_once('pdo.php');
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        // Requête préparée avec filtre sur category
        $stmt = $pdo->prepare("SELECT * FROM art WHERE category = :category");
        $stmt->execute(['category' => $this->category]);

        // Récupère tous les résultats en tableau d’objets
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $results;
    }
    public function getCategories()
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $stmt = $pdo->query("SELECT DISTINCT category FROM art ORDER BY category ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


public function getNomCreateur($id_user) {
    require_once('user.class.php');

    $user = new user();
    $createur = $user->getUserByID($id_user);
    return $createur ? $createur->username : "Inconnu";
}

public function getArtsByUserId($userId) {
    $stmt = $this->conn->prepare("SELECT * FROM art WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





}
?>