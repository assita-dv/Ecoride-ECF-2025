<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=ecoride_db;charset=utf8", 'root', '');

// Vérifie si un id conducteur est bien passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID du conducteur manquant.";
    exit;
}

$id_conducteur = (int) $_GET['id'];

// Récupérer le pseudo du conducteur
$stmt = $pdo->prepare("SELECT pseudo FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$id_conducteur]);
$conducteur = $stmt->fetch();

if (!$conducteur) {
    echo "Conducteur introuvable.";
    exit;
}

// Récupérer les avis du conducteur
$stmt = $pdo->prepare("
    SELECT a.*, u.pseudo AS pseudo_passager
    FROM avis a
    INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
    WHERE a.id_conducteur = ?
    ORDER BY a.date_avis DESC
");
$stmt->execute([$id_conducteur]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Les avis de <?= htmlspecialchars($conducteur['pseudo']) ?> - EcoRide</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
    <body style="background-color: #fffce0;" class="py-5">
<div class="container py-5">
    <h3 class="text-center text-success mb-4">
        Avis laissés pour <?= htmlspecialchars($conducteur['pseudo']) ?>
    </h3>

    <?php if (!empty($avis)): ?>
        <?php foreach ($avis as $a): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success"><?= htmlspecialchars($a['pseudo_passager']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($a['date_avis']) ?></h6>
                    <p class="card-text">
    <strong>Note :</strong>
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <i class="<?= $i <= $a['note'] ? 'fas' : 'far' ?> fa-star text-warning"></i>
    <?php endfor; ?></p>
                    <p class="card-text"><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Ce conducteur n’a pas encore reçu d’avis.</div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="/conducteur/05_profil_conducteur.php?id=<?= $id_conducteur ?>" class="btn btn-success">
            <i class="fas fa-arrow-left me-2"></i> Retour au profil
        </a>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-key.js" crossorigin="anonymous"></script>
</body>
</html>
