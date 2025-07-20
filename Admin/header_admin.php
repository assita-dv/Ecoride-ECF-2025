
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 mb-0">
    <span class="navbar-brand"><i class="fas fa-user-cog me-2"></i> Espace Administrateur</span>
    <div class="ms-auto">
        <a href="../deconnexion.php" class="btn btn-outline-light"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</nav>
