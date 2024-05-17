<?php
require_once("database/config.php");

class CompteBancaire {
    public static function getAllAccounts() {
        global $conn;
        $sql = "SELECT comptebancaire.*, client.nom, client.prenom 
                FROM comptebancaire 
                JOIN client ON comptebancaire.id_client = client.id_client";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAccountById($id_compte) {
        global $conn;
        $sql = "SELECT * FROM comptebancaire WHERE id_compte = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_compte]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateSolde($id_compte, $nouveau_solde) {
        global $conn;
        $sql = "UPDATE comptebancaire SET solde = ? WHERE id_compte = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nouveau_solde, $id_compte]);
    }

    public static function addOperation($id_compte, $type_operation, $montant) {
        global $conn;
        $sql = "INSERT INTO operationbancaire (id_compte, type_operation, montant, date_operation) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id_compte, $type_operation, $montant]);
    }

    public static function depot($id_compte, $montant) {
        $compte = self::getAccountById($id_compte);
        $nouveau_solde = $compte['solde'] + $montant;
        if (self::updateSolde($id_compte, $nouveau_solde)) {
            return self::addOperation($id_compte, 'depot', $montant);
        }
        return false;
    }

    public static function retrait($id_compte, $montant) {
        $compte = self::getAccountById($id_compte);
        $nouveau_solde = $compte['solde'] - $montant;
        if ($nouveau_solde >= 0) {
            if (self::updateSolde($id_compte, $nouveau_solde)) {
                return self::addOperation($id_compte, 'retrait', $montant);
            }
        }
        return false;
    }

    public static function transferFunds($id_compte_source, $id_compte_dest, $montant) {
        try {
            global $conn;
            $conn->beginTransaction();

            $compte_source = self::getAccountById($id_compte_source);
            $compte_dest = self::getAccountById($id_compte_dest);

            $nouveau_solde_source = $compte_source['solde'] - $montant;
            $nouveau_solde_dest = $compte_dest['solde'] + $montant;

            if ($nouveau_solde_source >= 0) {
                self::updateSolde($id_compte_source, $nouveau_solde_source);
                self::updateSolde($id_compte_dest, $nouveau_solde_dest);
                self::addOperation($id_compte_source, 'virement', $montant);
                self::addOperation($id_compte_dest, 'virement', $montant);

                $conn->commit();
                return true;
            } else {
                $conn->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}
?>