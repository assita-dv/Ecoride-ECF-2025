<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');

// Récupérer les employés et les conducteurs
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE role IN ('employe', 'conducteur')");
$stmt->execute();
$comptes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    
<div class="container mt-5">
    <h2 class="mb-4 text-success"><i class="fas fa-users-cog me-2"></i>Gérer les Comptes</h2>

    <?php if (empty($comptes)) : ?>
        <div class="alert alert-info">Aucun compte employé ou conducteur trouvé.</div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success text-center">
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($comptes as $compte) : ?>
                        <tr>
                            <td><?= $compte['id_utilisateur'] ?></td>
                            <td><?= htmlspecialchars($compte['pseudo']) ?></td>
                            <td><?= htmlspecialchars($compte['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $compte['role'] === 'employe' ? 'primary' : 'warning' ?>">
                                    <?= ucfirst($compte['role']) ?>
                                </span>
                            </td>
                            <td>
                               
                                <?= $compte['suspendu'] == 1 ? '<span class="text-danger">Suspendu</span>' : '<span class="text-success">Actif</span>' ?>

                            </td>
                            <td>
                                <form action="traiter_compte.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $compte['id_utilisateur'] ?>">
                                    <input type="hidden" name="action" value="suspendre">
                                    <button class="btn btn-outline-warning btn-sm" onclick="return confirm('Suspendre ce compte ?')">
                                        <i class="fas fa-user-slash"></i> Suspendre
                                    </button>
                                </form>
                                <form action="traiter_compte.php" method="POST" class="d-inline ms-2">
                                    <input type="hidden" name="id" value="<?= $compte['id_utilisateur'] ?>">
                                    <input type="hidden" name="action" value="supprimer">
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce compte ?')">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>