<?php
// accueil_admin.php
session_start();

if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../connexion.php');
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');

// Total crédits gagnés
$total_credits = $pdo->query("SELECT SUM(c.montant) AS total FROM credits c")->fetch(PDO::FETCH_ASSOC);
$total = $total_credits['total'] ?? 0;
?>

<?php include('header_admin.php'); ?>
<?php include('sidebar_admin.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

   

<div class="container-fluid mt-4" style="margin-left: 20px">
    <div class="text-center mb-5">
        <h1 class="text-success"><i class="fas fa-chart-line me-2"></i> Statistiques de la plateforme</h1>
        <p class="lead">Suivi des covoiturages et des revenus en crédits</p>
    </div>





    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-car-side"></i> Covoiturages par jour
                </div>
                <div class="card-body">
                    <canvas id="chartCovoiturages"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-coins"></i> Crédits gagnés par jour
                </div>
                <div class="card-body">
                    <canvas id="chartCredits"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card text-white bg-dark">
        <div class="card-body">
            <h4 class="card-title"><i class="fas fa-wallet"></i> Total des crédits gagnés :</h4>
            <p class="card-text fs-3"><?= htmlspecialchars($total) ?> €</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// TODO : Ajouter les données réelles via PHP ou requêtes AJAX
const ctx1 = document.getElementById('chartCovoiturages').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'],
        datasets: [{
            label: 'Covoiturages',
            data: [3, 5, 2, 6, 4],
            borderColor: 'green',
            backgroundColor: 'rgba(0,128,0,0.1)'
        }]
    }
});

const ctx2 = document.getElementById('chartCredits').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'],
        datasets: [{
            label: 'Crédits (€)',
            data: [10, 20, 15, 25, 30],
            backgroundColor: 'green'
        }]
    }
});
</script>

</body>
</html>
