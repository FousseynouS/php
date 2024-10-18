<?php
// Inclure le fichier de configuration de la base de données
require_once '../database.php';

// Initialisation des variables
$message = '';

// Récupérer les auteurs pour le formulaire
$resultatAuteurs = $db->query("SELECT id_auteur, prenom_auteur, nom_auteur FROM auteur");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérez les données du formulaire et nettoyez les entrées
        $id_oeuvre = htmlspecialchars($_POST['id_oeuvre'], ENT_QUOTES, 'UTF-8');
        $nouveau_titre_oeuvre = htmlspecialchars($_POST['nouveau_titre_oeuvre'], ENT_QUOTES, 'UTF-8');
        $id_auteur = htmlspecialchars($_POST['auteur'], ENT_QUOTES, 'UTF-8');

        // Vérifiez que l'ID de l'œuvre est un entier
        if (!filter_var($id_oeuvre, FILTER_VALIDATE_INT)) {
            throw new Exception("ID de l'œuvre invalide.");
        }

        // Requête SQL pour mettre à jour le titre et l'auteur de l'œuvre
        $requete = "UPDATE OEUVRE SET titre_oeuvre = :titre_oeuvre, id_auteur = :id_auteur WHERE id_oeuvre = :id";
        $stmt = $db->prepare($requete);
        $stmt->bindParam(':id', $id_oeuvre, PDO::PARAM_INT);
        $stmt->bindParam(':titre_oeuvre', $nouveau_titre_oeuvre, PDO::PARAM_STR);
        $stmt->bindParam(':id_auteur', $id_auteur, PDO::PARAM_INT);

        // Exécutez la requête
        $stmt->execute();

        // Fermez la fenêtre pop-up et actualisez la liste des œuvres dans la fenêtre parente
        echo "<script>window.close(); window.opener.location.reload();</script>";
    } catch (PDOException $e) {
        // Gérer les erreurs de mise à jour de manière sécurisée
        die("Erreur lors de la mise à jour de l'œuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    } catch (Exception $e) {
        // Gérer les autres erreurs
        die("Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
} else {
    // Récupérez l'ID de l'œuvre à modifier depuis l'URL et nettoyez l'entrée
    $id_oeuvre = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

    // Vérifiez que l'ID de l'œuvre est un entier
    if (!filter_var($id_oeuvre, FILTER_VALIDATE_INT)) {
        die("ID de l'œuvre invalide.");
    }

    // Récupérez les informations actuelles de l'œuvre
    try {
        $requete_info_oeuvre = "SELECT titre_oeuvre, id_auteur FROM OEUVRE WHERE id_oeuvre = :id";
        $stmt_info_oeuvre = $db->prepare($requete_info_oeuvre);
        $stmt_info_oeuvre->bindParam(':id', $id_oeuvre, PDO::PARAM_INT);
        $stmt_info_oeuvre->execute();
        $info_oeuvre = $stmt_info_oeuvre->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si l'œuvre existe
        if (!$info_oeuvre) {
            die("Aucune œuvre trouvée avec cet ID.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des informations de l'œuvre : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Oeuvre</title>
</head>
<body>
    <h1>Modifier l'œuvre</h1>
    <form method="post" action="modifier-oeuvre.php">
        <!-- Champ caché pour l'ID de l'œuvre -->
        <input type="hidden" name="id_oeuvre" value="<?= htmlspecialchars($id_oeuvre, ENT_QUOTES, 'UTF-8') ?>">
        
        <label for="nouveau_titre_oeuvre">Nouveau titre :</label>
        <input type="text" name="nouveau_titre_oeuvre" id="nouveau_titre_oeuvre" value="<?= htmlspecialchars($info_oeuvre['titre_oeuvre'], ENT_QUOTES, 'UTF-8') ?>" required><br>

        <label for="auteur">Choisissez un auteur :</label>
        <select name="auteur" id="auteur" required>
            <option value="">Choisissez un auteur</option>
            <?php while ($auteur = $resultatAuteurs->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $auteur['id_auteur']; ?>" <?php if ($auteur['id_auteur'] == $info_oeuvre['id_auteur']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($auteur['prenom_auteur'] . ' ' . $auteur['nom_auteur']); ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <input type="submit" value="Modifier">
    </form>
</body>
</html>
