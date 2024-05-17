<?php
require_once("model/CompteBancaire.php");

$comptes = CompteBancaire::getAllAccounts();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_compte = $_POST['id_compte'];
    $montant = (float) $_POST['montant'];
    $action = $_POST['action'];

    if ($action == 'virement' && isset($_POST['id_compte_dest'])) {
        $id_compte_dest = $_POST['id_compte_dest'];
        if (CompteBancaire::transferFunds($id_compte, $id_compte_dest, $montant)) {
            $message = "Virement de $montant franc cfa vers le compte $id_compte_dest effectué avec succès.";
        } else {
            $message = "Erreur lors du virement.";
        }
    } else {
        if ($action == 'depot') {
            if (CompteBancaire::depot($id_compte, $montant)) {
                $message = "Dépôt de $montant franc cfa effectué avec succès.";
            } else {
                $message = "Erreur lors du dépôt.";
            }
        } elseif ($action == 'retrait') {
            if (CompteBancaire::retrait($id_compte, $montant)) {
                $message = "Retrait de $montant franc cfa effectué avec succès.";
            } else {
                $message = "Erreur lors du retrait.";
            }
        }
    }

    $comptes = CompteBancaire::getAllAccounts();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Comptes</title>
    <link rel="stylesheet" type="text/css" href="style/gestion_compte.css">
    <script>
        function toggleVirement() {
            var virementSection = document.getElementById("virementSection");
            var virementRadio = document.getElementById("virement");
            virementSection.style.display = virementRadio.checked ? "block" : "none";
        }
    </script>
</head>
<body>
    <h1>Gestion des Comptes</h1>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Numéro de Compte</th>
                <th>Solde</th>
                <th>Propriétaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comptes as $compte): ?>
                <tr>
                    <td><?php echo htmlspecialchars($compte['numero_compte']); ?></td>
                    <td><?php echo htmlspecialchars($compte['solde']); ?></td>
                    <td><?php echo htmlspecialchars($compte['nom'] . " " . $compte['prenom']); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id_compte" value="<?php echo $compte['id_compte']; ?>">
                            <input type="number" step="0.01" name="montant" placeholder="Montant" required>
                            <br>
                            <input type="radio" name="action" id="depot" value="depot" onclick="toggleVirement()" checked> Dépôt
                            <input type="radio" name="action" id="retrait" value="retrait" onclick="toggleVirement()"> Retrait
                            <input type="radio" name="action" id="virement" value="virement" onclick="toggleVirement()"> Virement
                            <br>
                            <div id="virementSection" style="display:none;">
                                <label for="id_compte_dest">Compte de destination:</label>
                                <select name="id_compte_dest">
                                    <option value="">Sélectionner un compte</option>
                                    <?php foreach ($comptes as $c): ?>
                                        <?php if ($c['id_compte'] != $compte['id_compte']): ?>
                                            <option value="<?php echo $c['id_compte']; ?>"><?php echo $c['numero_compte']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <br>
                            <button type="submit" name="submit">Effectuer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php" class="button">Accueil</a>
</body>
</html>