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

// Récupération de l'identifiant du conducteur
$id_utilisateur = $_GET['id'] ?? null;

if (!$id_utilisateur) {
    die("Identifiant du conducteur non spécifié.");
}

// Requête pour récupérer les infos du conducteur
$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_utilisateur' => $id_utilisateur]);
$conducteur = $stmt->fetch();

if (!$conducteur) {
    die("Conducteur non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil du conducteur - EcoRide</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Profil du conducteur</h1>

    <div class="profil-conducteur">
        <img src="<?= htmlspecialchars($conducteur['photo'] ?? 'images/icon h.PNG') ?>" alt="Photo du conducteur" class="photo-conducteur">

        <h2><?= htmlspecialchars($conducteur['prenom']) ?> <?= htmlspecialchars($conducteur['nom']) ?></h2>
        <p><strong>Pseudo :</strong> <?= htmlspecialchars($conducteur['pseudo']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($conducteur['email']) ?></p>
        <!-- Ajoute ici d'autres infos si tu veux, comme un numéro ou une bio -->
    </div>

    <a href="javascript:history.back()">🔙 Revenir aux résultats</a>

</body>
</html>
