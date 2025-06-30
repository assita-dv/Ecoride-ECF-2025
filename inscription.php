

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

// Traitement du formulaire
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = 'utilisateur'; // ou 'conducteur' selon ton usage
    $vehicule = $_POST['vehicule'];
    $plaque = $_POST['plaque'];
    $nb_place = $_POST['nb_place'];
    $fumeur = $_POST['fumeur'];
    $musique = $_POST['musique'];
    $animaux = $_POST['animaux'];
    $conversation = $_POST['conversation'];

    // Gestion de l'upload de photo
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $dossier = 'uploads/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }
        $photo_name = uniqid() . '_' . $_FILES['photo']['name'];
        $photo_path = $dossier . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        $photo = $photo_path;
    }

    // Insertion dans la table conducteur
    $stmt = $pdo->prepare("INSERT INTO conducteur (pseudo, email, mot_de_passe, nom, prenom, role, photo) 
                           VALUES (:pseudo, :email, :mot_de_passe, :nom, :prenom, :role, :photo)");
    $stmt->execute([
        ':pseudo' => $pseudo,
        ':email' => $email,
        ':mot_de_passe' => $mot_de_passe,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':role' => $role,
        ':photo' => $photo
    ]);

    // Insertion dans la table conducteur
    $stmt2 = $pdo->prepare("INSERT INTO conducteur (pseudo, vehicule, plaque, nb_place, fumeur, musique, animaux, conversation) 
                            VALUES (:pseudo, :vehicule, :plaque, :nb_place, :fumeur, :musique, :animaux, :conversation)");
    $stmt2->execute([
        ':pseudo' => $pseudo,
        ':vehicule' => $vehicule,
        ':plaque' => $plaque,
        ':nb_place' => $nb_place,
        ':fumeur' => $fumeur,
        ':musique' => $musique,
        ':animaux' => $animaux,
        ':conversation' => $conversation
    ]);

    $message = "Inscription réussie ! Bienvenue $prenom 🎉";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription conducteur</title>
   <link rel="stylesheet" href="css/style.css">
</head>

<body id="body-inscription">
    <h2 id="h2-inscription">🚗 Inscription Conducteur EcoRide</h2> 

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

   


    <div class="form-container">
    <form id="form-inscription" method="POST" enctype="multipart/form-data">
        
        <div class="role-selection">
            <label><input type="radio" name="role" value="utilisateur" checked onchange="toggleConducteur(false)"> Passager</label>
            <label><input type="radio" name="role" value="conducteur" onchange="toggleConducteur(true)"> Conducteur</label>
        </div>

        <!-- Champs communs -->
        <label>Pseudo</label>
        <input type="text" name="pseudo" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mot de passe</label>
        <input type="password" name="mot_de_passe" required>

        <label>Nom</label>
        <input type="text" name="nom" required>

        <label>Prénom</label>
        <input type="text" name="prenom" required>

        <label>Photo de profil (facultatif)</label>
        <input type="file" name="photo" accept="image/*">

        <!-- ✅ CHAMPS SPÉCIFIQUES CONDUCTEUR -->
        <div class="conducteur-fields">
            <hr>
            <label>Modèle du véhicule</label>
            <input type="text" name="vehicule" required>

            <label>Numéro de plaque</label>
            <input type="text" name="plaque" required>

            <label>Nombre de places disponibles</label>
            <input type="number" name="nb_place" required>

            <label>Fumeur ?</label>
            <select name="fumeur">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>

            <label>Musique ?</label>
            <select name="musique">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>

            <label>Animaux acceptés ?</label>
            <select name="animaux">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>

            <label>Conversation ?</label>
            <select name="conversation">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>
        </div>

        <button type="submit" class="btn">S'inscrire</button>
    </form>
</div>

    <script src="js/inscription.js" defer></script>
</body>

</html>
