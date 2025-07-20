

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employe') {
    header('Location: ../connexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Employé - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"><style>
  body {
    margin: 0;
    padding: 0;
  }

  .vh-100 {
    margin-top: -24px; 
  }
</style>

</head>
<body class="bg-light">

<?php include('header_employe.php'); ?>
<div class="vh-100 position-relative">
    <!-- Image de fond -->
    <img src="/images-ecoride/employe_acceuil.png"
         class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
         alt="Image accueil employé"
         style="z-index: 1; object-fit: cover;">

    <!-- Carte de bienvenue centrée -->
    <div class="position-absolute top-50 start-50 translate-middle" style="z-index: 2;">
        <div class="card text-center border-success shadow" style="background-color: rgba(255, 255, 255, 0.95); max-width: 600px;">
            <div class="card-body p-4">
                <h1 class="card-title text-success mb-3">
                    <i class="fas fa-leaf me-2"></i> Bienvenue dans l’Espace Employé
                </h1>
                <p class="card-text fs-5">Gérez les avis et les trajets signalés avec sérieux et bienveillance </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>


