<?php

require_once("database/config.php");

class clientInfo {
    public static function getAllClients() {
        global $conn;
        $sql = "SELECT c.id_client, c.nom, c.prenom, c.adresse, c.telephone, cb.numero_compte 
                FROM client c 
                LEFT JOIN comptebancaire cb ON c.id_client = cb.id_client";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$clients = clientInfo::getAllClients();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations des Clients</title>
    <link rel="stylesheet" href="style\info.css">
</head>
<body>
    <h1>Informations des Clients</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Numéro de Compte</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['nom']); ?></td>
                    <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($client['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($client['telephone']); ?></td>
                    <td><?php echo htmlspecialchars($client['numero_compte']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php" class="button">Retour à l'Accueil</a>
</body>
</html>