<?php
require_once("database/config.php");
require_once("Client.php");

$nomErr = $prenomErr = $adresseErr = $telephoneErr = "";
$nom = $prenom = $adresse = $telephone = "";
$isFormValid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['nom'])) {
        $nomErr = "Le nom est requis";
        $isFormValid = false;
    } else {
        $nom = test_input($_POST['nom']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $nom)) {
            $nomErr = "Seules les lettres et les espaces sont autorisés";
            $isFormValid = false;
        }
    }

    if (empty($_POST['prenom'])) {
        $prenomErr = "Le prénom est requis";
        $isFormValid = false;
    } else {
        $prenom = test_input($_POST['prenom']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $prenom)) {
            $prenomErr = "Seules les lettres et les espaces sont autorisés";
            $isFormValid = false;
        }
    }

    if (empty($_POST['adresse'])) {
        $adresseErr = "L'adresse est requise";
        $isFormValid = false;
    } else {
        $adresse = test_input($_POST['adresse']);
    }

    if (empty($_POST['telephone'])) {
        $telephoneErr = "Le téléphone est requis";
        $isFormValid = false;
    } else {
        $telephone = test_input($_POST['telephone']);
        if (!preg_match("/^[0-9]{9}$/", $telephone)) {
            $telephoneErr = "Le téléphone doit comporter 9 chiffres";
            $isFormValid = false;
        }
    }

    if ($isFormValid) {
        $id_client = Client::addClient($nom, $prenom, $adresse, $telephone);
        $numero_compte = generateAccountNumber();
        $solde = 0;
        $sql = "INSERT INTO comptebancaire (numero_compte, solde, id_client) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$numero_compte, $solde, $id_client]);
        
        echo "<script>
                alert('Client ajouté avec succès !');
                window.location.href = 'index.php';
              </script>";
        exit();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateAccountNumber() {
    return "" . rand(1000, 9999);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <link rel="stylesheet" href="style/creation_compte.css">
</head>
<body>
    <h1>Création de Compte</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>">
        <span><?php echo $nomErr; ?></span><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>">
        <span><?php echo $prenomErr; ?></span><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>">
        <span><?php echo $adresseErr; ?></span><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>">
        <span><?php echo $telephoneErr; ?></span><br>

        <input type="submit" value="Créer le Compte">
    </form>
    <br>
    <a href="index.php" class="button">Retour à l'accueil</a>
</body>
</html>