

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

session_start(); 
unset($_SESSION['username']);
unset($_SESSION['password']);

// Détruire la session
session_destroy();




header('Location: index.php');
exit();


// Afficher les erreurs en PHP
// (fonctionne à condition d’avoir activé l’option en local)
error_reporting(error_level: E_ALL);
ini_set("display_errors", 1);
?>
    <h1>Deconnexion</h1>
</body>
</html>