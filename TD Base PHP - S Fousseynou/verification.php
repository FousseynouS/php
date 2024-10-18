<?php
session_start(); // Je démarre la session pour pouvoir stocker des informations utilisateur

// Je définis les informations de connexion correctes
$bon_username = "fousseynou";
$bon_password = "admin";

// Je crée une variable pour stocker les messages d'erreur
$error_message = ""; 

// Je vérifie si le formulaire a été soumis avec la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Je récupère le nom d'utilisateur et le mot de passe saisis par l'utilisateur
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : ''; 


    if ($username === $bon_username && $password === $bon_password) {
        echo "Connexion réussie ! Bienvenue, " . htmlspecialchars($username) . "!";
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}




// Afficher les erreurs en PHP
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<form action="deconnexion.php" method="post">
        <input type="submit" value="Deconnexion">
    </form>

<?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>
