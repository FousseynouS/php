<?php
// Inclusion du fichier de connexion à la base de données
require '../database.php';

if (isset($_GET['id_oeuvre'])) {
    try {
        // Récupérer et valider l'ID de l'oeuvre
        $id_oeuvre = htmlspecialchars($_GET['id_oeuvre'], ENT_QUOTES, 'UTF-8');

        // Vérifier si l'ID est valide
        if (!filter_var($id_oeuvre, FILTER_VALIDATE_INT)) {
            throw new Exception("ID de l'oeuvre invalide.");
        }

        // Commencer une transaction
        $db->beginTransaction();

        // Supprimer d'abord toutes les œuvres associées à cet oeuvre
        $sql_delete_oeuvres = "DELETE FROM OEUVRE WHERE id_oeuvre = :id_oeuvre";
        $stmt_delete_oeuvres = $db->prepare($sql_delete_oeuvres);
        $stmt_delete_oeuvres->bindParam(':id_oeuvre', $id_oeuvre, PDO::PARAM_INT);
        $stmt_delete_oeuvres->execute();

        // Ensuite, supprimer l'oeuvre lui-même
        $sql_delete_oeuvre = "DELETE FROM OEUVRE WHERE id_oeuvre = :id_oeuvre";
        $stmt_delete_oeuvre = $db->prepare($sql_delete_oeuvre);
        $stmt_delete_oeuvre->bindParam(':id_oeuvre', $id_oeuvre, PDO::PARAM_INT);
        $stmt_delete_oeuvre->execute();

        // Valider la transaction
        $db->commit();

        // Redirection vers la page des oeuvres après la suppression
        header('Location: afficher-oeuvres.php');
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, annuler la transaction et afficher un message d'erreur sécurisé
        $db->rollBack();
        die("Erreur lors de la suppression : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    } catch (Exception $e) {
        // Gérer les autres erreurs
        die("Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
} else {
    // Gestion de l'erreur si l'ID de l'oeuvre n'est pas défini
    die("ID de l'oeuvre non spécifié.");
}
?>