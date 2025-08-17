

<?php
session_start();

// Sécurité
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'employe') {
    header("Location: ../connexion.php");
    exit;
}

include_once '../config.php';

// Trajets signalés
$trajets_signales = $pdo->query("
    SELECT p.*, 
           u1.pseudo AS passager, u1.email AS email_passager,
           u2.pseudo AS conducteur, u2.email AS email_conducteur,
           c.ville_depart, c.ville_arrivee, c.date_depart
    FROM participe p
    JOIN utilisateur u1 ON p.id_utilisateur = u1.id_utilisateur
    JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
    JOIN utilisateur u2 ON c.id_utilisateur = u2.id_utilisateur
    WHERE p.colonne_validation = 'non'
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trajets signalés - Espace Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include('header_employe.php'); ?>

<div class="container mt-5">
    <h2><i class="fas fa-exclamation-triangle me-2 text-danger"></i> Trajets signalés</h2>

    <?php if (empty($trajets_signales)) : ?>
        <div class="alert alert-success mt-4"> Aucun trajet n’a été signalé comme problématique.</div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-2 g-4 mt-3">
            <?php foreach ($trajets_signales as $trajet) : ?>
                <div class="col">
                    <div class="card border-danger shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-danger">
                                <i class="fas fa-route me-1"></i>
                                <?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                            </h5>
                            <p class="card-text">
                                <strong><i class="fas fa-calendar-alt"></i> Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?><br>
                                <strong><i class="fas fa-user"></i> Passager :</strong> <?= htmlspecialchars($trajet['passager']) ?> (<?= htmlspecialchars($trajet['email_passager']) ?>)<br>
                                <strong><i class="fas fa-user-tie"></i> Conducteur :</strong> <?= htmlspecialchars($trajet['conducteur']) ?> (<?= htmlspecialchars($trajet['email_conducteur']) ?>)<br>
                                <strong><i class="fas fa-comment-dots"></i> Commentaire :</strong><br>
                                <em><?= nl2br(htmlspecialchars($trajet['commentaire_probleme'])) ?></em>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
