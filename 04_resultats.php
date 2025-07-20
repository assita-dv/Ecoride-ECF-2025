<?php
include_once './config.php'; 

// Récupération et nettoyage des données GET
$ville_depart   = trim($_GET['ville_depart'] ?? '');
$ville_arrivee  = trim($_GET['ville_arrivee'] ?? '');
$date_depart    = $_GET['date_depart'] ?? '';
$heure_depart   = $_GET['heure_depart'] ?? '';

// Conversion éventuelle de date (JJ/MM/AAAA → YYYY-MM-DD)

if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $date_depart)) {
    $parts = explode('/', $date_depart);
    $date_depart = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
}
// Construction dynamique de la requête
$sql = "SELECT c.*, u.nom, u.prenom, u.pseudo, u.email, u.photo, c.id_utilisateur 
        FROM covoiturage c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        WHERE 1";
$params = [];

if (!empty($heure_depart)) {
    $sql .= " AND HOUR(c.heure_depart) = HOUR(:heure_depart) AND MINUTE(c.heure_depart) = MINUTE(:heure_depart)";
    $params[':heure_depart'] = $heure_depart . ":00"; // je force le format HH:MM:SS
}
if (!empty($ville_arrivee)) {
    $sql .= " AND LOWER(c.ville_arrivee) = LOWER(:ville_arrivee)";
    $params[':ville_arrivee'] = $ville_arrivee;
}
if (!empty($date_depart)) {
    $sql .= " AND c.date_depart = :date_depart";
    $params[':date_depart'] = $date_depart;
}
if (!empty($heure_depart)) {
    $sql .= " AND TIME_FORMAT(c.heure_depart, '%H:%i') = :heure_depart";
    $params[':heure_depart'] = $heure_depart;
}

$sql .= " ORDER BY c.date_depart ASC, c.heure_depart ASC";

// Exécution
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de trajets - EcoRide</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body class="body-resultat">

<div class="container my-5">
    
    <h2 class="text-muted mb-4"><?= count($resultats) ?> trajet(s) trouvé(s)</h2>

    <?php if (count($resultats) > 0): ?>
        <?php foreach ($resultats as $trajet): ?>
            <div class="card mb-4 shadow-sm rounded-4 p-3">
                <div class="row align-items-center">

                    <!-- Col gauche : heure et ville -->
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <strong><?= htmlspecialchars($trajet['heure_depart']) ?></strong>
                            </div>
                            <div class="text-muted small">
                                Durée estimée : 2h00
                            </div>
                        </div>
                        <div class="fw-bold fs-6">
                            <?= htmlspecialchars($trajet['ville_depart']) ?><span class="ligne-trajet mx-2">
                            <span class="rond"></span><span class="trait"></span><span class="rond"></span></span>
                            <?= htmlspecialchars($trajet['ville_arrivee']) ?>

                        </div>
                    </div>

                    <div class="col-md-4 text-end">
                        <div class="fs-5 fw-bold text-success"><?= htmlspecialchars($trajet['prix']) ?> €</div>
                       
                    </div>
                </div>

                <!-- Séparateur -->
                <hr class="my-3">

                <!-- photo conducteur et infos -->
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="<?= !empty($trajet['photo']) ? 'uploads/' . htmlspecialchars($trajet['photo']) : 'images/default_avatar.png' ?>"
                             alt="photo conducteur" class="rounded-circle me-2" width="50" height="50">
                    </div>
                    <div class="col">
                        <strong><?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars($trajet['nom']) ?></strong>
                        <div class="text-muted small">@<?= htmlspecialchars($trajet['pseudo']) ?></div>
                        <div class="text-success small mt-1"> <i class="fas fa-car text-success me-1"></i> Voyage écologique</div>
                    </div>
                    <div class="col-md-4 text-end">
                      <a href="/conducteur/05_profil_conducteur.php?id=<?= $trajet['id_utilisateur'] ?>" class="btn btn-outline-success btn-sm mt-2">Voir le profil</a>
                    <a href="/utilisateur/confirmer_participation_etape.php?id=<?= $trajet['id_covoiturage'] ?>" class="btn btn-success mt-2">Participer</a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning mt-4"> Aucun trajet trouvé.</div>
    <?php endif; ?>

     <a href="index.php" class="btn btn-secondary mt-3"> Revenir à l'accueil</a>

</div>

</body>
</html>









