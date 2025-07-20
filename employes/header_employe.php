
<?php
// Sécurité : vérifier le rôle employé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employe') {
    header('Location: ../connexion.php');
    exit;
}
?>

<!-- -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success px-4 mb-4">
    <span class="navbar-brand"><i class="fas fa-user-shield me-2"></i> Espace Employé</span>
    <div class="ms-auto">
        <a href="accueil_employe.php" class="btn btn-outline-light me-2">
            <i class="fas fa-home"></i> Accueil
        </a>
        <a href="avis_en_attente.php" class="btn btn-outline-light me-2">
            <i class="fas fa-comments"></i> Avis
        </a>
        <a href="trajets_signales.php" class="btn btn-outline-light me-2">
            <i class="fas fa-exclamation-triangle"></i> Trajets signalés
        </a>
        <a href="../deconnexion.php" class="btn btn-light">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</nav>
