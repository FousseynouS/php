<!DOCTYPE html>
<html>
<head>
    <title>Liste des Oeuvres</title>
</head>
<body>
    <?php
    // Inclure le fichier de configuration de la base de données
    require_once '../database.php';

    try {
        // Requête SQL pour récupérer la liste des oeuvres
        $requete = "SELECT id_oeuvre,prenom_auteur, nom_auteur, titre_oeuvre FROM oeuvre JOIN auteur ON oeuvre.id_auteur = auteur.id_auteur";
        $resultat = $db->query($requete);

        // Affichage de la liste des oeuvres avec les boutons "Modifier" et "Supprimer"
        echo "<h1>Liste des oeuvres</h1>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID de <br>l'oeuvre</br></th><th>Auteur</th><th>Titre</th><th>Modifier</th><th>Supprimer</th></tr>"; // CHECKPOINT 

        while ($oeuvre = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$oeuvre['id_oeuvre']}</td>";
            echo "<td> {$oeuvre['prenom_auteur']} {$oeuvre['nom_auteur']}</td>";
            echo "<td>{$oeuvre['titre_oeuvre']}</td>";
            // Bouton "Modifier" qui ouvre une fenêtre pop-up pour modifier l'oeuvre
            echo "<td><button onclick='modifierOeuvre({$oeuvre['id_oeuvre']})'>Modifier</button></td>";
            // Bouton "Supprimer" avec confirmation
            echo "<td><button onclick='confirmationSuppOeuvre({$oeuvre['id_oeuvre']})'>Supprimer</button></td>";
            echo "</tr>";
        }

        echo "</table>";
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des oeuvres : " . $e->getMessage());
    }


    
    ?>

    <!-- Bouton pour ajouter un oeuvre dans une petite fenêtre -->
    <br>
    <button onclick="ajouterOeuvre()">Ajouter une oeuvre</button>

    <!-- Bouton pour renvoyer vers la liste des œuvres -->
    <button onclick="window.location.href='../gestion-auteurs/afficher-auteurs.php'">Afficher les auteurs</button>

    <!-- Scripts JavaScript pour les fenêtres pop-up et la suppression -->
    <script>
        function modifierOeuvre(id_oeuvre) {
            // Ouvrir une fenêtre pop-up avec le formulaire de modification de l'oeuvre
            var popupWindow = window.open('modifier-oeuvre.php?id=' + id_oeuvre, 'Modifier Oeuvre', 'width=400,height=300');
        }

        function confirmationSuppOeuvre(id_oeuvre) {
            // Demander une confirmation avant de supprimer une oeuvre et toutes ses œuvres associées
            if (confirm("Êtes-vous sûr de vouloir supprimer cette oeuvre et toutes ses œuvres associées ?")) {
                // Rediriger vers la page de suppression avec l'ID de l'oeuvre
                window.location.href = 'supprimer-oeuvre.php?id_oeuvre=' + id_oeuvre;
            }
        }

        function ajouterOeuvre() {
            // Ouvrir une fenêtre pop-up pour ajouter un oeuvre
            var popupWindow = window.open('ajouter-oeuvres.php', 'Ajouter Oeuvre', 'width=400,height=300');
        }
    </script>
</body>
</html>
