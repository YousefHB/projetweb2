<?php
class connexion
{
    function CNXbase()
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=e-artistry", "root", ""); // change les identifiants si besoin
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
?>
