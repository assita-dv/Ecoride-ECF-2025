
<?php
session_start();


include_once '../config.php';



// Récupération de l'ID utilisateur


$id_utilisateur = $_SESSION['id_utilisateur'];


// Sécurité
if (!isset($_SESSION['pseudo']) || $_SESSION['role'] !== 'conducteur') {
    header('Location: connexion.php');
    exit;
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $depart = htmlspecialchars($_POST['depart']);
    $destination = htmlspecialchars($_POST['destination']);
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $places = (int) $_POST['places'];
    $prix = (float) $_POST['prix'];

    // Valeurs par défaut
    $id_voiture = 1; 
    $voyage_ecologique = 1;

    // Insertion dans la base
    $sql = "INSERT INTO covoiturage (ville_depart, ville_arrivee, date_depart, heure_depart, nb_place, prix, id_voiture, voyage_ecologique, id_utilisateur)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

$stmt->execute([$depart, $destination, $date, $heure, $places, $prix, $id_voiture, $voyage_ecologique, $id_utilisateur]);

    $message = "Trajet publié avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposer un voyage – EcoRide</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="body-trajet">



<div class="container-form-proposer">
    <h1 class="titre-form-proposer">Proposer un voyage</h1>
    <?php if ($message): ?>
        <p class="message-form-proposer"><?= $message ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="formulaire-proposer">
        <label class="label-proposer">Lieu de départ
            <input type="text" name="depart" required placeholder="Ex : Lyon" class="input-proposer">
        </label>

        <label class="label-proposer">Destination
            <input type="text" name="destination" required placeholder="Ex : Paris" class="input-proposer">
        </label>

        <label class="label-proposer">Date
            <input type="date" name="date" required class="input-proposer">
        </label>

        <label class="label-proposer">Heure
            <input type="time" name="heure" required class="input-proposer">
        </label>

        <label class="label-proposer">Places disponibles
            <input type="number" name="places" required min="1" max="8" class="input-proposer">
        </label>

        <label class="label-proposer">Prix par passager (€)
            <input type="number" name="prix" required min="0" step="0.5" class="input-proposer">
        </label>

        <button type="submit" class="btn-proposer">Publier</button>
    </form>
</div>

<div class="button_retour">
  <a href="espace_conducteur.php" class="retour-button">Retour</a>
</div>
</body>
</html>
