<?php
include_once 'config.php';

// Récupération et nettoyage des données GET
$ville_depart   = trim($_GET['ville_depart'] ?? '');
$ville_arrivee  = trim($_GET['ville_arrivee'] ?? '');
$date_depart    = $_GET['date_depart'] ?? '';
$heure_depart   = $_GET['heure_depart'] ?? '';

// Conversion éventuelle de date (JJ/MM/AAAA → YYYY-MM-DD)

if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $date_depart)) {
    $parts = explode('/', $date_depart);
    $date_depart = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
}
// Construction dynamique de la requête
$sql = "SELECT c.*, u.nom, u.prenom, u.pseudo, u.email, u.photo, c.id_utilisateur 
        FROM covoiturage c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        WHERE 1";
$params = [];

if (!empty($heure_depart)) {
    $sql .= " AND HOUR(c.heure_depart) = HOUR(:heure_depart) AND MINUTE(c.heure_depart) = MINUTE(:heure_depart)";
    $params[':heure_depart'] = $heure_depart . ":00"; // je force le format HH:MM:SS
}
if (!empty($ville_arrivee)) {
    $sql .= " AND LOWER(c.ville_arrivee) = LOWER(:ville_arrivee)";
    $params[':ville_arrivee'] = $ville_arrivee;
}
if (!empty($date_depart)) {
    $sql .= " AND c.date_depart = :date_depart";
    $params[':date_depart'] = $date_depart;
}
if (!empty($heure_depart)) {
    $sql .= " AND TIME_FORMAT(c.heure_depart, '%H:%i') = :heure_depart";
    $params[':heure_depart'] = $heure_depart;
}

$sql .= " ORDER BY c.date_depart ASC, c.heure_depart ASC";

// Exécution
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Résultats de trajets - EcoRide</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="body-resultat">

  <div class="container my-5">
    <h2 class="text-muted mb-4"><?= count($resultats) ?> trajet(s) trouvé(s)</h2>

    <?php
      // 👉 une seule source de rendu
      require __DIR__ . '/partials/trajets_list.php';
    ?>

    <div class="button_retour mt-4">
      <a href="index.php" class="retour-button">Retour</a>
    </div>
  </div>

</body>
</html>


