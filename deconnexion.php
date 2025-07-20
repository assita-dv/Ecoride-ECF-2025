<?php
session_start();
session_unset();     // Supprime toutes les variables de session
session_destroy();   // Détruit la session

// Redirige vers la page de connexion
header("Location: ../connexion.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!-- Bouton déconnexion -->
<div class="text-center mt-4">
    <a href="deconnexion.php" class="btn btn-danger">
        <i class="fas fa-sign-out-alt"></i> Se déconnecter
    </a>
</div>
</body>
</html>
