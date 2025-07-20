

<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.php");
    exit;
}

$status = $_GET['status'] ?? '';
$message = '';

if ($status === 'ok') {
    $message = "<i class='fas fa-check-circle text-success me-2'></i> Félicitations ! Vous participez maintenant à ce covoiturage.";
} elseif ($status === 'deja_participant') {
    $message = "<i class='fas fa-info-circle text-primary me-2'></i> Vous avez déjà réservé ce covoiturage.";
} else {
    $message = "<i class='fas fa-exclamation-circle text-danger me-2'></i> Une erreur est survenue.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #fffce0;
            font-family: 'Segoe UI', sans-serif;
        }

        .confirmation-box {
            margin-top: 100px;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        h3 {
            font-weight: 600;
            color: #2c662d;
        }

        .btn-green {
            background-color: #198754;
            color: white;
        }

        .btn-green:hover {
            background-color: #146c43;
        }

        .fa-icon {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="confirmation-box text-center">
            <h3><?= $message ?></h3>
            <hr class="my-4">
            <a href="/index.php" class="btn btn-green me-2">
                <i class="fas fa-home me-1"></i> Retour à l'accueil
            </a>
            <a href="espace_utilisateur.php" class="btn btn-green">
                <i class="fas fa-user-circle me-1"></i> Mon espace
            </a>
        </div>
    </div>
</body>
</html>
