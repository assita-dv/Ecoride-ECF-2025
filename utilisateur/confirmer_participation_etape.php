<?php
session_start();

include_once '../config.php';

// Vérifier la connexion
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupérer le covoiturage concerné
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_covoiturage = (int)$_GET['id'];

// Récupérer les infos du covoiturage


$stmt = $pdo->prepare("
    SELECT c.*, u.pseudo, u.photo, v.energie
    FROM covoiturage c
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    LEFT JOIN voiture v ON u.id_utilisateur = v.id_utilisateur
    WHERE c.id_covoiturage = ?
");

$stmt->execute([$id_covoiturage]);
$trajet = $stmt->fetch();

if (!$trajet) {
    die("Trajet non trouvé.");
}

// Vérifier les crédits de l'utilisateur

$stmt_credit = $pdo->prepare("SELECT credits FROM utilisateur WHERE id_utilisateur = ?");
$stmt_credit->execute([$id_utilisateur]);
$credit_user = $stmt_credit->fetchColumn();

$prix_trajet = (int)$trajet['prix'];
$credit_restant = $credit_user - $prix_trajet;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de participation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background-color:#fffce0;">
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h3 class="text-success mb-4">Confirmer votre participation</h3>

        <p><strong>Trajet :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></p>
        <p><strong>Conducteur :</strong> <?= htmlspecialchars($trajet['pseudo']) ?></p>
        <p><strong>Énergie :</strong> <?= htmlspecialchars($trajet['energie'] ?? 'Non précisé') ?></p>
        <p><strong>Prix du trajet :</strong> <span class="text-primary"><?= $prix_trajet ?> crédits</span></p>
        <p><strong>Vos crédits actuels :</strong> <?= $credit_user ?> → <strong>Restera : </strong> <?= max($credit_restant, 0) ?> crédits</p>

        <?php if ($credit_user >= $prix_trajet): ?>
            <form method="post" action="participer.php">
                <input type="hidden" name="id_covoiturage" value="<?= $id_covoiturage ?>">
                <button type="submit" class="btn btn-success">Confirmer ma participation</button>
                <a href="javascript:history.back()" class="btn btn-outline-secondary ms-2">Annuler</a>
            </form>
        <?php else: ?>
            <div class="alert alert-danger mt-3">
                Vous n'avez pas assez de crédits pour participer à ce trajet.
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
