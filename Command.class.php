<?php

class Command {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $product_id, $price) {
        $sql = "INSERT INTO commandes (user_id, product_id, prix_total, date_commande)
                VALUES (:user_id, :product_id, :prix_total, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':prix_total', $price);

        if ($stmt->execute()) {
            return $this->pdo->lastInsertId(); // Retourne l'ID de la commande créée
        } else {
            throw new Exception("Erreur lors de l'enregistrement de la commande.");
        }
    }
}
