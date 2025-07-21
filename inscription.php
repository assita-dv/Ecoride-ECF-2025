
<?php
// Connexion à la base de données
include_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];

    // Crédits offerts à l’inscription
    $credits_offerts = 20;

    // Upload photo
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $dossier = 'uploads/';
        if (!is_dir($dossier)) mkdir($dossier, 0777, true);
        $photo_name = uniqid() . '_' . $_FILES['photo']['name'];
        $photo_path = $dossier . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        $photo = $photo_path;
    }

    // Insertion dans la table utilisateur (avec crédits)
    $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, email, mot_de_passe, nom, prenom, role, photo, credits) 
                           VALUES (:pseudo, :email, :mot_de_passe, :nom, :prenom, :role, :photo, :credits)");
    $stmt->execute([
        ':pseudo' => $pseudo,
        ':email' => $email,
        ':mot_de_passe' => $mot_de_passe,
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':role' => $role,
        ':photo' => $photo,
        ':credits' => $credits_offerts
    ]);

    // Si c'est un conducteur, on insère aussi dans la table conducteur
    if ($role === 'conducteur') {
        $vehicule = $_POST['vehicule'];
        $plaque = $_POST['plaque'];
        $nb_place = $_POST['nb_place'];
        $fumeur = $_POST['fumeur'];
        $musique = $_POST['musique'];
        $animaux = $_POST['animaux'];
        $conversation = $_POST['conversation'];

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
    }
    $message = " Inscription réussie ! Bienvenue $prenom  Vous avez reçu 20 crédits pour commencer.";

    // Redirection vers la page de connexion après 2 secondes
    header("Refresh: 2; URL=connexion.php");
    // Optionnel : afficher le message 2 secondes avant redirection
    echo "<p style='color:green; text-align:center;'>$message<br> Redirection en cours vers la page de connexion...</p>";
    exit;

    
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

    <h2 id="h2-inscription">Inscription  EcoRide</h2> 

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="form-container">
        <form id="form-inscription" method="POST" enctype="multipart/form-data">
         
<!-- Sélection du rôle -->
<div class="role-selection">
    <label><input type="radio" name="role" value="utilisateur" checked onchange="toggleConducteur(false)"> Passager</label>
    <label><input type="radio" name="role" value="conducteur" onchange="toggleConducteur(true)"> Conducteur</label>
    <label><input type="radio" name="role" value="employe" onchange="toggleConducteur(false)"> Employé</label>
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

            <!-- Champs conducteur uniquement -->
            <div class="conducteur-fields">
                <hr>
                <label>Modèle du véhicule</label>
                <input type="text" name="vehicule">

                <label>Numéro de plaque</label>
                <input type="text" name="plaque">

                <label>Nombre de places disponibles</label>
                <input type="number" name="nb_place">

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

    <script>
    function toggleConducteur(show) {
        const fields = document.querySelector('.conducteur-fields');
        fields.style.display = show ? 'block' : 'none';
    }

    // Initialisation (cache les champs conducteur si "utilisateur" est coché par défaut)
    window.onload = () => {
        const isConducteur = document.querySelector('input[name="role"]:checked').value === 'conducteur';
        toggleConducteur(isConducteur);
    };
    </script>

</body>
</html>
