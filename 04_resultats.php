





<?php 
// Connexion à la base de données
$host = 'localhost';
$dbname = 'ecoride_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des données du formulaire
$ville_depart = $_GET['ville_depart'] ?? '';
$ville_arrivee = $_GET['ville_arrivee'] ?? '';
$date_depart = $_GET['date_depart'] ?? '';
$heure_depart = $_GET['heure_depart'] ?? '';
 $date_depart = $_GET['date_depart'] ?? '';

// Convertir si nécessaire
if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $date_depart)) {
    $parts = explode('/', $date_depart);
    $date_depart = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
}
echo "<pre>";
var_dump($ville_depart, $ville_arrivee, $date_depart, $heure_depart);
echo "</pre>";

// Vérification si les champs sont vides
if (empty($ville_depart) || empty($ville_arrivee) || empty($date_depart) || empty($heure_depart)) {
    echo "<p style='color:red;'>Erreur : Veuillez remplir tous les champs du formulaire de recherche.</p>";
    echo "<a href='index.php'>🔙 Revenir à l'accueil</a>";
    exit;
}

// Requête SQL
$sql = "SELECT c.*, u.nom, u.prenom, u.pseudo, u.email
        FROM covoiturage c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        WHERE LOWER(c.ville_depart) = LOWER(:ville_depart)
        AND LOWER(c.ville_arrivee) = LOWER(:ville_arrivee)
        AND c.date_depart = :date_depart
        AND c.heure_depart = :heure_depart";




$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':ville_depart' => $ville_depart,
    ':ville_arrivee' => $ville_arrivee,
    ':date_depart' => $date_depart,
    ':heure_depart' => $heure_depart . ":00" // ← car la BDD stocke '08:00:00'
]);


$resultats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de trajets - EcoRide</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Résultats de votre recherche</h1>

    <?php if (count($resultats) > 0): ?>
        <?php foreach ($resultats as $trajet): ?>
            <div class="carte-trajet">
                <img src="<?= htmlspecialchars($trajet['photo'] ?? 'images/icon h.PNG') ?>" alt="Photo du conducteur" class="photo-conducteur">

                <div class="infos-trajet">
                    <h3><?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars($trajet['nom']) ?> (<?= htmlspecialchars($trajet['pseudo']) ?>)</h3>
                    <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?> → <strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
                    <p><strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></p>
                    <p><strong>Prix :</strong> <?= htmlspecialchars($trajet['prix']) ?> € | <strong>Places :</strong> <?= htmlspecialchars($trajet['nb_place']) ?></p>
                    <p><strong>Voyage écologique :</strong> <?= $trajet['voyage_ecologique'] ? '🌱 Oui' : 'Non' ?></p>
                    <a href="05_profil_conducteur.php?id=<?= $trajet['id_utilisateur'] ?>" class="btn-detail">Voir le profil</a>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p style='color:orange;'>🚫 Aucun trajet trouvé pour votre recherche.</p>
    <?php endif; ?>

    <a href="index.php">🔙 Revenir à l'accueil</a>
</body>
</html>


