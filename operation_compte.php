<?php
require_once("OperationBancaire.php");

$comptes = OperationBancaire::getAllAccounts();
$transactions = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['compte'])) {
    $selected_account = $_POST['compte'];
    $transactions = OperationBancaire::getTransactions($selected_account);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opérations Bancaires</title>
    <link rel="stylesheet" type="text/css" href="style/operation_compte.css">
</head>
<body>
    <div class="container">
        <h1>Opérations Bancaires</h1>
        <form method="post">
            <label for="compte">Sélectionnez un compte :</label>
            <select name="compte" id="compte">
                <?php foreach ($comptes as $compte) : ?>
                    <option value="<?php echo $compte['id_compte']; ?>"><?php echo $compte['numero_compte']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Afficher les transactions">
        </form>

        <?php if (!empty($transactions)) : ?>
            <h2>Transactions pour le compte <?php echo htmlspecialchars($selected_account); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Opération</th>
                        <th>Type Opération</th>
                        <th>Montant</th>
                        <th>Date Opération</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id_operation']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['type_operation']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['montant']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['date_operation']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php" class="button">Retour à l'accueil</a>
    </div>
</body>
</html>