<?php
require_once ("database/config.php");

class Client {
    public static function addClient($nom, $prenom, $adresse, $telephone) {
        global $conn;
        $sql = "INSERT INTO client (nom, prenom, adresse, telephone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nom, $prenom, $adresse, $telephone]);
        return $conn->lastInsertId();
    }
}
?>