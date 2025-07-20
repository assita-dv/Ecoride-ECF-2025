<?php
session_start();

// Connexion base de données
$pdo = new PDO("mysql:host=localhost;dbname=ecoride_db;charset=utf8", 'root', '');

// Vérification de connexion
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: /connexion.php");
    exit;
}

$id_passager = $_SESSION['id_utilisateur'];

// Récupération de l'id du conducteur via GET
if (!isset($_GET['id_conducteur'])) {
    echo "<p class='text-danger'>Conducteur introuvable.</p>";
    exit;
}

$id_conducteur = (int) $_GET['id_conducteur'];

// Vérifier que le conducteur existe
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ? AND role = 'conducteur'");
$stmt->execute([$id_conducteur]);
$conducteur = $stmt->fetch();

if (!$conducteur) {
    echo "<p class='text-danger'>Ce conducteur n'existe pas.</p>";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = (int) $_POST['note'];
    $commentaire = htmlspecialchars($_POST['commentaire']);

    // Insertion
    $stmt = $pdo->prepare("INSERT INTO avis (id_utilisateur, id_conducteur, note, commentaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id_passager, $id_conducteur, $note, $commentaire]);

    echo "<div class='alert alert-success'>Merci pour votre avis !</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Laisser un avis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
    <body style="background-color: #fffce0;" class="py-5">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-success">Laisser un avis sur <?= htmlspecialchars($conducteur['pseudo']) ?></h3>
        <form method="post">
            <div class="mb-3">
                <label for="note" class="form-label">Note (1 à 5)</label>
                <select name="note" id="note" class="form-select" required>
                    <option value="">-- Choisir une note --</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> / 5</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="commentaire" class="form-label">Commentaire</label>
                <textarea name="commentaire" id="commentaire" rows="4" class="form-control" placeholder="Votre avis..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Envoyer l'avis</button>
            <a href="javascript:history.back()" class="btn btn-outline-secondary ms-2">Retour</a>
        </form>
    </div>
</div>
</body>
</html>
