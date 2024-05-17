<?php
require_once("database/config.php");

class OperationBancaire {
    public static function getAllAccounts() {
        global $conn;
        $sql = "SELECT * FROM CompteBancaire";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTransactions($compte) {
        global $conn;
        $sql = "SELECT * FROM OperationBancaire WHERE id_compte = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$compte]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>