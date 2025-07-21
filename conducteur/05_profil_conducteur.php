



<?php
// Connexion à la base de données
include_once '../config.php';

// Vérification ID
$id_conducteur = isset($_GET['id']) ? (int) $_GET['id'] : null;
if (!$id_conducteur) die("ID conducteur manquant.");

// Récupération infos du conducteur
$sql = "SELECT u.*, c.vehicule, c.plaque, c.nb_place, c.fumeur, c.musique, c.animaux, c.conversation
        FROM utilisateur u
        LEFT JOIN conducteur c ON u.pseudo = c.pseudo
        WHERE u.id_utilisateur = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_conducteur]);
$conducteur = $stmt->fetch();
if (!$conducteur) die("Conducteur introuvable.");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil du conducteur - EcoRide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="background-color: #fffce0;">

<div class="container my-5">
    <h4 class="mb-4 text-success"><i class="fas fa-user me-2"></i>Profil du conducteur</h4>

    <div class="card shadow p-4 rounded-4">
        <div class="row g-4">

            <!-- Photo -->
            <div class="col-md-4 text-center">
                <img src="../uploads/<?= htmlspecialchars($conducteur['photo']) ?>" class="rounded-circle border border-success" style="width: 140px; height: 140px; object-fit: cover;">
                <p class="mt-3 text-muted">Conducteur vérifié</p>
            </div>

            <!-- Infos -->
            <div class="col-md-8">
                <h2 class="mb-0"><?= htmlspecialchars($conducteur['prenom']) ?> <?= htmlspecialchars($conducteur['nom']) ?></h2>
                <p class="text-muted">@<?= htmlspecialchars($conducteur['pseudo']) ?></p>
                <p><i class="fas fa-envelope text-success me-2"></i><?= htmlspecialchars($conducteur['email']) ?></p>

                <hr>

                <div class="row">
                    <!-- Véhicule -->
                    <div class="col-md-6">
                        <h5 class="text-success"><i class="fas fa-car me-2"></i>Véhicule</h5>
                        <p><i class="fas fa-car-side text-muted me-2"></i><?= htmlspecialchars($conducteur['vehicule']) ?></p>
                        <p><i class="fas fa-id-card text-muted me-2"></i>Plaque : <?= htmlspecialchars($conducteur['plaque']) ?></p>
                        <p><i class="fas fa-users text-muted me-2"></i>Places : <?= htmlspecialchars($conducteur['nb_place']) ?></p>
                    </div>

                    <!-- Préférences -->
                    <div class="col-md-6">
                        <h5 class="text-success"><i class="fas fa-sliders-h me-2"></i>Préférences</h5>
                        <p>
                            <i class="fas fa-smoking me-2 <?= $conducteur['fumeur'] === 'oui' ? 'text-danger' : 'text-muted' ?>"></i>
                            Fumeurs : <?= $conducteur['fumeur'] === 'oui' ? 'Acceptés' : 'Non' ?>
                        </p>
                        <p>
                            <i class="fas fa-music me-2 <?= $conducteur['musique'] === 'oui' ? 'text-success' : 'text-muted' ?>"></i>
                            Musique : <?= $conducteur['musique'] === 'oui' ? 'Oui' : 'Non' ?>
                        </p>
                        <p>
                            <i class="fas fa-dog me-2 <?= $conducteur['animaux'] === 'oui' ? 'text-success' : 'text-muted' ?>"></i>
                            Animaux : <?= $conducteur['animaux'] === 'oui' ? 'Acceptés' : 'Non' ?>
                        </p>
                        <p>
                            <i class="fas fa-comments me-2 <?= $conducteur['conversation'] === 'oui' ? 'text-success' : 'text-muted' ?>"></i>
                            Conversation : <?= $conducteur['conversation'] === 'oui' ? 'Oui' : 'Non' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avis -->
        <div class="mt-5">
            <h5 class="text-success"><i class="fas fa-comments me-2"></i> Avis reçus</h5>
            <?php
            $stmt = $pdo->prepare("
                SELECT a.*, u.pseudo 
                FROM avis a
                JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                WHERE a.id_conducteur = ?
                ORDER BY a.date_avis DESC
                LIMIT 3
            ");
            $stmt->execute([$id_conducteur]);
            $avis = $stmt->fetchAll();

            if ($avis):
                foreach ($avis as $a):
            ?>
            <div class="card bg-light p-3 mb-3 rounded">
                <strong><?= htmlspecialchars($a['pseudo']) ?></strong>
                <span class="text-muted small">– <?= htmlspecialchars($a['date_avis']) ?></span><br>
                <div class="mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="<?= $i <= $a['note'] ? 'fas' : 'far' ?> fa-star text-warning"></i>
                    <?php endfor; ?>
                </div>
                <?= htmlspecialchars($a['commentaire']) ?>
            </div>
            <?php endforeach; ?>
            <a href="/liste_avis.php?id=<?= $id_conducteur ?>" class="text-success">Voir tous les avis</a>
            <?php else: ?>
            <p class="text-muted">Ce conducteur n’a pas encore reçu d’avis.</p>
            <?php endif; ?>
        </div>

        <!-- Boutons -->
        <div class="mt-4 d-flex gap-3">
            <a href="javascript:history.back()" class="btn btn-success">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
              <a href="/laisser_avis.php?id_conducteur=<?= $conducteur['id_utilisateur'] ?>" class="btn btn-outline-success">

    <i class="fas fa-star me-2"></i> Donner un avis
</a>
  </div>
    </div>
</div>

</body>
</html>
