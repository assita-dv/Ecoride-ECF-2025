<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

include_once '../config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, email, mot_de_passe, role) VALUES (?, ?, ?, 'employe')");
    $stmt->execute([$pseudo, $email, $mot_de_passe_hache]);

    $message = " Employé ajouté avec succès.";
}
?>

<?php include("header_admin.php"); ?>
<?php include('sidebar_admin.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-success"><i class="fas fa-user-plus me-2"></i>Ajouter un employé</h2>

    <?php if ($message) : ?>
        <div class="alert alert-success mt-3"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Pseudo</label>
            <input type="text" name="pseudo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse e-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
</div>
</body>
</html>