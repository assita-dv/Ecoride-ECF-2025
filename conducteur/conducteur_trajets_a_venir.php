<?php
session_start();

include_once '../config.php';


// Vérifier si le conducteur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.php");
    exit;
}

$id_conducteur = $_SESSION['id_utilisateur'];

// Récupérer les trajets futurs du conducteur
$sql = "SELECT * FROM trajet 
        WHERE conducteur_id = :conducteur_id 
        AND date_depart >= CURDATE()
        ORDER BY date_depart ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['conducteur_id' => $id_conducteur]);
$trajets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes trajets à venir - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light" style="background-color: #fffce0;">
<div class="container my-5">
    <h2 class="text-center text-success mb-4"><i class="fas fa-road"></i> Mes trajets à venir</h2>

    <?php if (count($trajets) === 0): ?>
        <div class="alert alert-info text-center">Aucun trajet à venir pour le moment.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($trajets as $trajet): ?>
                <div class="list-group-item rounded-4 mb-3 shadow-sm">
                    <h5><i class="fas fa-location-dot text-success"></i> Départ : <?= htmlspecialchars($trajet['ville_depart']) ?></h5>
                    <p><i class="fas fa-map-marker-alt text-success"></i> Arrivée : <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
                    <p><i class="fas fa-calendar-alt text-success"></i> Date : <?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                    <p><i class="fas fa-euro-sign text-success"></i> Prix : <?= htmlspecialchars($trajet['prix']) ?> €</p>
                    <p><i class="fas fa-users text-success"></i> Places restantes : <?= htmlspecialchars($trajet['nb_places']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="../espace_conducteur.php" class="btn btn-outline-success">
            <i class="fas fa-arrow-left"></i> Retour à mon espace </a>
    
    </div>
</div>

<!-- FontAwesome + Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
