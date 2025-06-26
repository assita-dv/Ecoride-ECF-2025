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

// Requête SQL préparée
$sql = "SELECT c.*, u.nom, u.prenom, u.pseudo, u.email, u.photo
        FROM covoiturage c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        WHERE c.ville_depart = :ville_depart 
        AND c.ville_arrivee = :ville_arrivee 
        AND c.heure_depart = :heure_depart
        AND c.date_depart = :date_depart";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':ville_depart' => $ville_depart,
    ':ville_arrivee' => $ville_arrivee,
    ':heure_depart' => $heure_depart,
    ':date_depart' => $date_depart
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
        <ul>
            <?php foreach ($resultats as $trajet): ?>
                <li>
                    <strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?><br>
                    <strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?><br>
                    <strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?><br>
                    <strong>Heure :</strong> <?= htmlspecialchars($trajet['heure_depart']) ?><br>
                    <strong>Prix :</strong> <?= htmlspecialchars($trajet['prix']) ?> €<br>
                    <strong>Places disponibles :</strong> <?= htmlspecialchars($trajet['nb_place']) ?><br>
                    <strong>Voyage écologique :</strong> <?= $trajet['voyage_ecologique'] ? '🌱 Oui' : 'Non' ?>
                </li>

                <!-- Carte du trajet (à placer dans la boucle) -->
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
        </ul>
    <?php else: ?>
        <p>Aucun trajet trouvé pour votre recherche.</p>
    <?php endif; ?>

    <a href="index.php">🔙 Revenir à l'accueil</a>
</body>
</html>
