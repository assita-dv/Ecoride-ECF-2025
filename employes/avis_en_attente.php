<?php
include('header_employe.php');

// Connexion à la base
$pdo = new PDO("mysql:host=localhost;dbname=ecoride_db;charset=utf8", 'root', '');

// Récupérer les avis en attente
$stmt = $pdo->query("
    SELECT a.*, u.pseudo AS passager, c.pseudo AS conducteur
    FROM avis a
    JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
    JOIN utilisateur c ON a.id_conducteur = c.id_utilisateur
    WHERE a.valide = 0
    ORDER BY a.date_avis DESC
");
$avis_en_attente = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Avis en attente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<div class="container mt-5">
 
<h2 class="text-sussed mb-4">
    <i class="fas fa-clipboard-list me-2"></i> Avis à valider
</h2>

  <?php if (empty($avis_en_attente)): ?>
    <div class="alert alert-info">Aucun avis en attente de validation.</div>
  <?php else: ?>
    <?php foreach ($avis_en_attente as $avis): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($avis['passager']) ?> ➝ <?= htmlspecialchars($avis['conducteur']) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted"><?= $avis['date_avis'] ?></h6>
          <p>Note : <?= $avis['note'] ?>/5</p>
          <p><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></p>

          <a href="valider_avis.php?id=<?= $avis['id_avis'] ?>&action=valider" class="btn btn-success btn-sm me-2"> Valider</a>
          <a href="valider_avis.php?id=<?= $avis['id_avis'] ?>&action=refuser" class="btn btn-danger btn-sm"> Refuser</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
