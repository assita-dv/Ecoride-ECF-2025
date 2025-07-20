
<?php
session_start();

// Sécurité : redirection si l'utilisateur n'est pas conducteur
if (!isset($_SESSION['pseudo']) || $_SESSION['role'] !== 'conducteur') {
    header('Location: connexion.php');
    exit;
}

$pseudo = $_SESSION['pseudo'];
$credits = 18;

include_once '../config.php';

// Récupérer les infos du conducteur via son pseudo
$sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudo";
$stmt = $pdo->prepare($sql);
$stmt->execute([':pseudo' => $pseudo]);
$conducteur = $stmt->fetch();

// Récupérer les trajets liés à ce conducteur
$id_utilisateur = $conducteur['id_utilisateur'] ?? null;
$today = date('Y-m-d');

// Trajets à venir
$sql_venir = "SELECT * FROM covoiturage WHERE id_utilisateur = ? AND date_depart >= ? ORDER BY date_depart ASC";
$stmt_venir = $pdo->prepare($sql_venir);
$stmt_venir->execute([$id_utilisateur, $today]);
$trajets_a_venir = $stmt_venir->fetchAll();

// Historique des trajets
$sql_passe = "SELECT * FROM covoiturage WHERE id_utilisateur = ? AND date_depart < ? ORDER BY date_depart DESC";
$stmt_passe = $pdo->prepare($sql_passe);
$stmt_passe->execute([$id_utilisateur, $today]);
$trajets_passes = $stmt_passe->fetchAll();


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Conducteur – EcoRide</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

 <body class= " body-conducteur " >
<div class="container-conducteur">

    <!-- BOUTON DECONNEXION  -->
    <div class="bloc-deconnexion text-start mb-4 ">
        <a href="/deconnexion.php" class="btn-deconnexion">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>

    <h1 class="titre-espace text-center text-success">Bienvenue dans votre espace personnel</h1>

    <!-- Carte profil -->
    <div class="carte-profil">
        <img src="../uploads/<?= htmlspecialchars($conducteur['photo']) ?>" alt="Photo du conducteur" class="photo-avatar">
        <div class="infos-utilisateur">
            <h2 class="pseudo"><?= htmlspecialchars($pseudo) ?></h2>
            <p class="credits"><i class="fas fa-wallet"></i>&nbsp;Crédits restants : <strong><?= $credits ?></strong></p>
        </div>
        <a href="modifier_profil_conducteur.php" class="modifier-profil" title="Modifier mon profil">
            <i class="fas fa-edit"></i>
        </a>
    </div>

    <!-- Boutons de navigation -->
    <div class="bloc-boutons">
        <button class="bouton-espace" onclick="toggleSection('trajets')">
            <i class="fas fa-truck"></i>&nbsp;Trajets à venir <i class="fas fa-chevron-down"></i>
        </button>
        <button class="bouton-espace" onclick="toggleSection('historique')">
            <i class="fas fa-history"></i>&nbsp;Historique de mes trajets <i class="fas fa-chevron-down"></i>
        </button>
        <button class="bouton-espace" onclick="toggleSection('paiements')">
            <i class="fas fa-credit-card"></i>&nbsp;Historique de mes paiements <i class="fas fa-chevron-down"></i>
        </button>
        <a href="proposer_trajet.php" class="btn-lien-trajet">
            <button class="bouton-espace">
                <i class="fas fa-plus-circle"></i>&nbsp;Ajouter un nouveau trajet
            </button>
        </a>
    </div>

    <!-- Bas de page -->
    <div class="bas-espace">
        <button class="bouton-espace" onclick="toggleSection('aide')">
            <i class="fas fa-question-circle"></i>&nbsp;Centre d’aide <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <!-- Sections-->

<!-- debut test -->
 <div id="trajets" class="section-conducteur d-none"> 

    <h4><i class="fas fa-car  text-secondary me-2"></i>Mes trajets à venir</h4>
    <?php if (!empty($trajets_a_venir)): ?>
        <?php foreach ($trajets_a_venir as $trajet): ?>
            <div class="card carte-trajet-conducteur mb-3 bg-white shadow-sm p-3">
                <strong><?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></strong><br>
                <span><i class="fas fa-calendar-alt me-1"></i> <?= htmlspecialchars($trajet['date_depart']) ?></span><br>
                <span><i class="fas fa-clock me-1"></i> <?= htmlspecialchars($trajet['heure_depart']) ?></span><br>
                <span><i class="fas fa-euro-sign me-1"></i> <?= htmlspecialchars($trajet['prix']) ?></span><br>

                <?php if ($trajet['statut'] === 'non_demarre'): ?>
                    <form method="POST" action="demarrer_trajet.php" class="mt-2">
                        <input type="hidden" name="id_covoiturage" value="<?= $trajet['id_covoiturage'] ?>">
                        <button class="btn btn-success btn-sm" type="submit">
                            <i class="fas fa-play"></i> Démarrer le trajet
                        </button>
                    </form>
                <?php elseif ($trajet['statut'] === 'en_cours'): ?>
                    <form method="POST" action="terminer_trajet.php" class="mt-2">
                        <input type="hidden" name="id_covoiturage" value="<?= $trajet['id_covoiturage'] ?>">
                        <button class="btn btn-warning btn-sm" type="submit">
                            <i class="fas fa-flag-checkered"></i> Arrivée à destination
                        </button>
                    </form>
                <?php else: ?>
                    <span class="badge bg-secondary mt-2">Trajet terminé</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Aucun trajet à venir pour le moment.</p>
    <?php endif; ?>
</div>

<!-- test -->
<div id="historique" class="section-conducteur d-none">
    
    <h4><i class="fas fa-history text-secondary me-2"></i>Historique de mes trajets</h4>
    <?php if (!empty($trajets_passes)): ?>
        <?php foreach ($trajets_passes as $trajet): ?>
            <div class="card mb-3 bg-light p-3">
                <strong><?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></strong><br>
                <span><i class="fas fa-calendar-alt me-1"></i> <?= htmlspecialchars($trajet['date_depart']) ?></span><br>
                <span><i class="fas fa-clock me-1"></i> <?= htmlspecialchars($trajet['heure_depart']) ?></span><br>
                <span><i class="fas fa-euro-sign me-1"></i> <?= htmlspecialchars($trajet['prix']) ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Historique vide.</p>
    <?php endif; ?>
</div>


<div id="paiements" class="section-conducteur d-none">
    <div class="card carte-trajet-conducteur mb-3 p-3">
        <h4><i class="fas fa-credit-card text-primary me-2"></i>Historique de mes paiements</h4>
        <p class="text-muted">Aucun paiement enregistré.</p>
    </div>
</div>

<div id="aide" class="section-conducteur d-none">
    <div class="card carte-trajet-conducteur mb-3 p-3">
        <h4><i class="fas fa-question-circle text-info me-2"></i>Centre d’aide</h4>
        <p class="text-muted">Besoin d’aide ? Contacte-nous !</p>
    </div>
</div>
</div>

<script src="/js/espace_conducteur.js"></script>
</body>
</html>
