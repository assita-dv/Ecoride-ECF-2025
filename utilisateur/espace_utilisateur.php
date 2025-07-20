

<?php
session_start();

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=ecoride_db;charset=utf8", 'root', '');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: /connexion.php");
    exit;
}

// Récupérer les infos utilisateur
$id_utilisateur = $_SESSION['id_utilisateur'];
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$utilisateur = $stmt->fetch();

$prenom = $utilisateur['prenom'] ?? '';
$pseudo = $utilisateur['pseudo'] ?? '';
$credits = $utilisateur['credits'] ?? 0;
$photo = $utilisateur['photo'] ?? 'images/default_avatar.png';

// Covoiturages passés
$stmt = $pdo->prepare("
    SELECT c.* 
    FROM covoiturage c
    INNER JOIN participe p ON c.id_covoiturage = p.id_covoiturage
    WHERE p.id_utilisateur = :id 
      AND (
          c.date_depart < CURDATE()
          OR (c.date_depart = CURDATE() AND c.heure_depart <= CURTIME())
      )
    ORDER BY c.date_depart DESC, c.heure_depart DESC
");
$stmt->execute([':id' => $id_utilisateur]);
$covoiturages_passes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Covoiturages à venir avec infos du conducteur
$stmt = $pdo->prepare("
    SELECT c.*, 
           u.id_utilisateur AS id_conducteur, 
           u.pseudo AS pseudo_conducteur, 
           u.photo AS photo_conducteur
    FROM covoiturage c
    INNER JOIN participe p ON c.id_covoiturage = p.id_covoiturage
    INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    WHERE p.id_utilisateur = :id 
      AND (
          c.date_depart > CURDATE()
          OR (c.date_depart = CURDATE() AND c.heure_depart > CURTIME())
      )
    ORDER BY c.date_depart ASC, c.heure_depart ASC
");
$stmt->execute([':id' => $id_utilisateur]);
$covoiturages_a_venir = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Passager – EcoRide</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="body-conducteur bg-light">

<div class="container-conducteur">

    <!-- Déconnexion -->
    <div class="bloc-deconnexion text-start mb-4">
        <a href="/deconnexion.php" class="btn-deconnexion">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>

    <h1 class="titre-espace text-center text-success">Bienvenue dans votre espace personnel</h1>

    <!-- Carte profil -->
    <div class="carte-profil">
        <img src="../<?= htmlspecialchars($photo) ?>" alt="Photo du passager" class="photo-avatar">
        <div class="infos-utilisateur">
            <h2 class="pseudo"><?= htmlspecialchars($pseudo) ?></h2>
            <p class="credits"><i class="fas fa-wallet"></i>&nbsp;Crédits restants : <strong><?= $credits ?></strong></p>
        </div>
    </div>

    <!-- Boutons navigation -->
    <div class="bloc-boutons">
        <button class="bouton-espace" onclick="toggleSection('trajets')">
            <i class="fas fa-car-side"></i>&nbsp;Covoiturages à venir <i class="fas fa-chevron-down"></i>
        </button>
        <button class="bouton-espace" onclick="toggleSection('paiements')">
            <i class="fas fa-credit-card"></i>&nbsp;Historique de mes paiements <i class="fas fa-chevron-down"></i>
        </button>
        <button class="bouton-espace" onclick="toggleSection('covoiturages_passes')">
            <i class="fas fa-clock"></i>&nbsp;Historique de mes covoiturages <i class="fas fa-chevron-down"></i>
        </button>
        <button class="bouton-espace" onclick="toggleSection('aide')">
            <i class="fas fa-question-circle"></i>&nbsp;Centre d’aide <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <!-- Trajets à venir -->

<div id="trajets" class="section-passager d-none">
    <div class="card carte-passager mb-4 p-4 bg-white shadow-sm" style="border-radius: 12px;">
        <h4 class="text-success mb-4">
            <i class="fas fa-car-side me-2"></i>Mes trajets à venir
        </h4>

        <?php if (!empty($covoiturages_a_venir)): ?>
            <?php foreach ($covoiturages_a_venir as $trajet): ?>
                <div class="card mb-4 p-4" style="border-left: 6px solid #28a745; border-radius: 10px;">
                    <p class="fw-bold fs-5 text-dark mb-3">
                        <?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?>
                    </p>
                    <p class="mb-3 text-dark" style="padding-left: 4px;">
                        <i class="fas fa-calendar-alt me-2 text-dark"></i><?= htmlspecialchars($trajet['date_depart']) ?>
                    </p>
                    <p class="mb-3 text-dark" style="padding-left: 4px;">
                        <i class="fas fa-clock me-2 text-dark"></i><?= htmlspecialchars($trajet['heure_depart']) ?>
                    </p>
                    <p class="mb-0 text-dark" style="padding-left: 4px;">
                        <i class="fas fa-euro-sign me-2 text-dark"></i><?= htmlspecialchars($trajet['prix']) ?> €
                    </p>
                  
<div class="mt-3">
    <a href="/conducteur/05_profil_conducteur.php?id=<?= $trajet['id_conducteur'] ?>" class="btn btn-outline-success btn-sm">
        Voir le profil du conducteur
    </a>
</div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aucun trajet à venir pour le moment.</p>
        <?php endif; ?>
    </div>
</div>


    <!-- Paiements -->
    <div id="paiements" class="section-passager d-none">
        <div class="card carte-passager mb-3 p-3">
            <h4><i class="fas fa-credit-card text-primary me-2"></i>Historique de mes paiements</h4>
            <p class="text-muted">Aucun paiement enregistré pour le moment.</p>
        </div>
    </div>

 
    <!-- Historique covoiturages -->
<div id="covoiturages_passes" class="section-passager d-none">
    <div class="card carte-passager mb-3 p-3">
        <h4><i class="fas fa-clock text-secondary me-2"></i>Historique de mes covoiturages</h4>

        <?php if (!empty($covoiturages_passes)): ?>
            <?php foreach ($covoiturages_passes as $trajet): ?>
                <div class="card mb-3 bg-light p-3">
                    <strong><?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?></strong><br>
                    <span><i class="fas fa-calendar-alt me-1"></i> <?= htmlspecialchars($trajet['date_depart']) ?></span><br>
                    <span><i class="fas fa-clock me-1"></i> <?= htmlspecialchars($trajet['heure_depart']) ?></span><br>
                    <span><i class="fas fa-euro-sign me-1"></i> <?= htmlspecialchars($trajet['prix']) ?> €</span><br>

                    <?php if ($trajet['statut'] === 'termine'): ?>
                        <form method="POST" action="valider_trajet.php" class="mt-2">
                            <input type="hidden" name="id_covoiturage" value="<?= $trajet['id_covoiturage'] ?>">
                            <label>Le trajet s’est-il bien passé ?</label><br>
                            <button type="submit" name="validation" value="oui" class="btn btn-success btn-sm">Oui</button>
                            <button type="button" onclick="afficherCommentaire(this)" class="btn btn-danger btn-sm">Non</button>

                            <div class="mt-2 commentaire-section d-none">
                                <textarea name="commentaire" class="form-control mb-2" placeholder="Décris le problème..."></textarea>
                                <button type="submit" name="validation" value="non" class="btn btn-warning btn-sm">Envoyer</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aucun covoiturage passé.</p>
        <?php endif; ?>
    </div>
</div>
    <!-- Aide -->
    <div id="aide" class="section-passager d-none">
        <div class="card carte-passager mb-3 p-3">
            <h4><i class="fas fa-question-circle text-info me-2"></i>Centre d’aide</h4>
            <p class="text-muted">Besoin d’aide ? Contacte-nous !</p>
        </div>
    </div>

</div>

<script>
function toggleSection(id) {
    const section = document.getElementById(id);
    section.classList.toggle('d-none');
}// nouvel 
function afficherCommentaire(btn) {
    const form = btn.closest("form");
    form.querySelector(".commentaire-section").classList.remove("d-none");
    btn.classList.add("d-none");
}
</script>

</body>
</html>
