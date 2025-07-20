

<?php
session_start();

// Sécurité
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'employe') {
    header("Location: ../connexion.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');

// Avis en attente
$avis_attente = $pdo->query("
    SELECT a.*, u.pseudo 
    FROM avis a 
    JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur 
    WHERE a.valide = 0
")->fetchAll(PDO::FETCH_ASSOC);

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
    <meta charset="UTF-8">
    <title>Espace Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 🔹 Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success px-4 mb-4">
    <span class="navbar-brand"><i class="fas fa-user-shield"></i> Espace Employé</span>
    <div class="ms-auto">
        <a href="../index.php" class="btn btn-outline-light me-2"><i class="fas fa-home"></i> Accueil</a>
        <a href="../deconnexion.php" class="btn btn-light"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</nav>

<div class="container">

    <!--  Avis à valider -->
    <section class="mb-5">
        <h3 class="mb-3 text-primary"><i class="fas fa-comments"></i> Avis à valider</h3>
        <?php if ($avis_attente): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($avis_attente as $avis): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user"></i> <?= htmlspecialchars($avis['pseudo']) ?> (<?= $avis['note'] ?>/5)</h5>
                                <p class="card-text"><i class="fas fa-quote-left text-muted"></i> <?= nl2br(htmlspecialchars($avis['commentaire'])) ?></p>
                                <form method="POST" action="traiter_avis.php" class="d-flex gap-2 mt-3">
                                    <input type="hidden" name="id_avis" value="<?= $avis['id_avis'] ?>">
                                    <button name="action" value="valider" class="btn btn-success btn-sm">
                                        <i class="fas fa-check-circle"></i> Valider
                                    </button>
                                    <button name="action" value="refuser" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times-circle"></i> Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Aucun avis en attente pour le moment.</p>
        <?php endif; ?>
    </section>

    <!-- Trajets signalés -->
    <section>
        <h3 class="mb-3 text-danger"><i class="fas fa-exclamation-triangle"></i> Trajets signalés comme mal passés</h3>
        <?php if ($trajets_signales): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($trajets_signales as $signalement): ?>
                    <div class="col">
                        <div class="card border-danger shadow-sm h-100">
                            <div class="card-body">
                                <p><strong><i class="fas fa-user"></i> Passager :</strong> <?= htmlspecialchars($signalement['passager']) ?> (<?= htmlspecialchars($signalement['email_passager']) ?>)</p>
                                <p><strong><i class="fas fa-car-side"></i> Conducteur :</strong> <?= htmlspecialchars($signalement['conducteur']) ?> (<?= htmlspecialchars($signalement['email_conducteur']) ?>)</p>
                                <p><strong><i class="fas fa-route"></i> Trajet :</strong> <?= htmlspecialchars($signalement['ville_depart']) ?> → <?= htmlspecialchars($signalement['ville_arrivee']) ?></p>
                                <p><strong><i class="fas fa-calendar-alt"></i> Date :</strong> <?= htmlspecialchars($signalement['date_depart']) ?></p>
                                <p><strong><i class="fas fa-comment-dots"></i> Problème :</strong><br><?= nl2br(htmlspecialchars($signalement['commentaire_probleme'])) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Aucun trajet signalé comme mal passé.</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>
