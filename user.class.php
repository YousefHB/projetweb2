<?php        
class user
{
/* attributs de la classe utilisateur*/
	
public $ID;
public $username;
public $password;
public $role;
public $profile_picture;
public $date_of_birth;
public $created_at;

/* constructeur de la classe */


public  function insertUser()
 {
require_once('pdo.php');
$cnx=new connexion();
$pdo=$cnx->CNXbase();
$date_now = date("Y-m-d H:i:s");
$req="INSERT INTO user (username,password,role,profile_picture,date_of_birth,created_at)
VALUES ('$this->username','$this->password','$this->role','$this->profile_picture','$this->date_of_birth','$date_now')";
$pdo->exec($req);
$error = $pdo->errorInfo();
if ($error[0] != "00000") {
    print_r($error);
}
}
public function getUserByID($id)
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $stmt = $pdo->prepare("SELECT * FROM user WHERE ID = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    return $user ?: null;
}
 public function getConnectedUserID()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  // Démarre la session si ce n'est pas déjà fait
        }
        if (isset($_SESSION["connecte"]) && $_SESSION["connecte"] === "1" && isset($_SESSION["ID"])) {
            return $_SESSION["ID"];
        }
        return null; // pas connecté
    }

/*
function getUser()
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    // Chercher uniquement par username
    $req = "SELECT * FROM user WHERE username='$this->username'";
    $res = $pdo->query($req);

    if ($res === false) {
        print_r($pdo->errorInfo());
    }

    return $res;
}*/
function getUser()
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    // Requête préparée pour éviter l'injection SQL
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->execute(['username' => $this->username]);

    return $stmt;
}


public function modifierUser($id)
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $req = "UPDATE user SET 
                username = '$this->username', 
                password = '$this->password', 
                profile_picture = '$this->profile_picture' 
            WHERE ID = $id";

    $pdo->exec($req) or print_r($pdo->errorInfo());
}


public function modifierUserSansPhoto($id)
{
    require_once('pdo.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();

    $req = "UPDATE user SET 
                username = '$this->username', 
                password = '$this->password' 
            WHERE ID = $id";

    $pdo->exec($req) or print_r($pdo->errorInfo());
}















}
?>