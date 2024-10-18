<?php
// Inclure le fichier de configuration de la base de données
require_once '../database.php';

// Initialiser une variable pour afficher les messages
$message = '';

// Récupérer les auteurs
$resultatAuteurs = $db->query("SELECT id_auteur, prenom_auteur, nom_auteur FROM auteur");

// Vérifiez si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyer et valider les données de formulaire
    $titre_oeuvre = htmlspecialchars($_POST['titre_oeuvre'], ENT_QUOTES, 'UTF-8');
    $id_auteur = htmlspecialchars($_POST['auteur'], ENT_QUOTES, 'UTF-8');

    // Vérifier si l'œuvre existe déjà
    $oeuvreExiste = false;
    try {
        // Requête SQL pour vérifier l'existence de l'œuvre
        $sql = "SELECT id_oeuvre FROM OEUVRE WHERE titre_oeuvre = :titre_oeuvre AND id_auteur = :id_auteur";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':titre_oeuvre', $titre_oeuvre, PDO::PARAM_STR);
        $stmt->bindParam(':id_auteur', $id_auteur, PDO::PARAM_STR);
        $stmt->execute();

        // Si l'œuvre existe déjà
        if ($stmt->rowCount() > 0) {
            $oeuvreExiste = true;
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la vérification de l'œuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    if (!$oeuvreExiste) {
        // L'œuvre n'existe pas, donc on peut l'ajouter
        try {
            $insertOeuvreSql = "INSERT INTO OEUVRE (titre_oeuvre, id_auteur) VALUES (:titre_oeuvre, :id_auteur)";
            $stmt = $db->prepare($insertOeuvreSql);
            $stmt->bindParam(':titre_oeuvre', $titre_oeuvre, PDO::PARAM_STR);
            $stmt->bindParam(':id_auteur', $id_auteur, PDO::PARAM_STR);
            $stmt->execute();

            $message = "Nouvelle œuvre ajoutée avec succès !";
            echo "<script>window.opener.location.reload();</script>";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout de l'œuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    } else {
        $message = "Cette œuvre existe déjà en base de données.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une nouvelle œuvre</title>
</head>
<body>
    <h1>Ajouter une nouvelle œuvre</h1>
    <p><?php echo $message; ?></p>

    <form method="POST" action="ajouter-oeuvres.php">
        <label for="titre_oeuvre">Titre de l'œuvre :</label>
        <input type="text" id="titre_oeuvre" name="titre_oeuvre" required><br>

        <label for="auteur">Sélectionnez un auteur :</label>
        <select name="auteur" id="auteur" required>
            <option value="">Choisissez un auteur</option>
            <?php while ($auteur = $resultatAuteurs->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $auteur['id_auteur']; ?>">
                    <?php echo htmlspecialchars($auteur['prenom_auteur'] . ' ' . $auteur['nom_auteur']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <input type="submit" value="Ajouter">
    </form>
</body>
</html>
