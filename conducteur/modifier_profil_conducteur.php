
<?php
session_start();
include_once '../config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

$id = $_SESSION['id_utilisateur'];

// Récupération des données actuelles
$req = $pdo->prepare("SELECT u.*, c.* FROM utilisateur u 
                      JOIN conducteur c ON u.pseudo = c.pseudo 
                      WHERE u.id_utilisateur = ?");
$req->execute([$id]);
$donnees = $req->fetch(PDO::FETCH_ASSOC);

//  Traitement du formulaire
if (isset($_POST['modifier'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $vehicule = $_POST['vehicule'];
    $plaque = $_POST['plaque'];
    $nb_place = $_POST['nb_place'];
    $fumeur = $_POST['fumeur'];
    $musique = $_POST['musique'];
    $animaux = $_POST['animaux'];
    $conversation = $_POST['conversation'];

    // Upload de l’image 
    $photo_profil = $donnees['photo'];

    if (!empty($_FILES['photo']['name'])) {
        $image_name = time() . '_' . basename($_FILES['photo']['name']);
        $upload_dir = 'uploads/';
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_profil = $image_name;
        }
    }

   
    $update_user = $pdo->prepare("UPDATE utilisateur SET pseudo = ?, email = ?, nom = ?, prenom = ?, photo = ? WHERE id_utilisateur = ?");
    $update_user->execute([$pseudo, $email, $nom, $prenom, $photo_profil, $id]);

    
    $update_conducteur = $pdo->prepare("UPDATE conducteur SET 
        vehicule = ?, plaque = ?, nb_place = ?, fumeur = ?, musique = ?, animaux = ?, conversation = ? 
        WHERE pseudo = ?");
    $update_conducteur->execute([$vehicule, $plaque, $nb_place, $fumeur, $musique, $animaux, $conversation, $pseudo]);

    echo "<p style='color:green;'> Profil mis à jour avec succès.</p>";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil Conducteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="background-color: #fffce0;" class="py-5">


<div class="container">
    <div class="card shadow p-4">
         <h2 class="mb-4 text-center text-success">
  <i class="fas fa-tools me-2"></i> Modifier mon profil conducteur</h2>

        <form action="" method="POST" enctype="multipart/form-data" class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Pseudo</label>
                <input type="text" name="pseudo" value="<?= htmlspecialchars($donnees['pseudo']) ?>" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($donnees['email']) ?>" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($donnees['nom']) ?>" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($donnees['prenom']) ?>" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Photo de profil</label><br>
                <?php if (!empty($donnees['photo'])): ?>
            
                    <img src="/uploads/<?= htmlspecialchars($donnees['photo']) ?>" width="100" alt="photo de profil" class="mb-2 rounded">

                <?php endif; ?>
                <input type="file" name="photo" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Modèle de véhicule</label>
                <input type="text" name="vehicule" value="<?= htmlspecialchars($donnees['vehicule']) ?>" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Numéro de plaque</label>
                <input type="text" name="plaque" value="<?= htmlspecialchars($donnees['plaque']) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Nombre de places</label>
                <input type="number" name="nb_place" value="<?= htmlspecialchars($donnees['nb_place']) ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Fumeur ?</label>
                <select name="fumeur" class="form-select">
                    <option value="oui" <?= ($donnees['fumeur'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= ($donnees['fumeur'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Musique ?</label>
                <select name="musique" class="form-select">
                    <option value="oui" <?= ($donnees['musique'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= ($donnees['musique'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Animaux acceptés ?</label>
                <select name="animaux" class="form-select">
                    <option value="oui" <?= ($donnees['animaux'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= ($donnees['animaux'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Conversation ?</label>
                <select name="conversation" class="form-select">
                    <option value="oui" <?= ($donnees['conversation'] == 'oui') ? 'selected' : '' ?>>Oui</option>
                    <option value="non" <?= ($donnees['conversation'] == 'non') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-between mt-4">
               <a href="espace_conducteur.php" class="btn btn-outline-secondary">
               <i class="fas fa-arrow-left me-2"></i> Retour</a>
                <button type="submit" name="modifier" class="btn btn-success"><i class="fas fa-save me-2"></i> Enregistrer les modifications</button>
            </div>

        </form>
    </div>
</div>

</body>
</html>