<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "config/bdd.php"; // 🔧 adapte ce chemin si besoin

    $email = trim($_POST["email"]);
    $mot_de_passe = $_POST["mot_de_passe"];

    // Recherche de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur["mot_de_passe"])) {
        $_SESSION["pseudo"] = $utilisateur["pseudo"];
        $_SESSION["role"] = $utilisateur["role"];

        // Redirection selon le rôle
        if ($utilisateur["role"] === "conducteur") {
            header("Location: profil_conducteur.php");
            exit;
        } else {
            header("Location: profil_utilisateur.php");
            exit;
        }
    } else {
        $message = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - EcoRide</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="body-connexion">

    <div class="form-container">
    <h2 class="form-title">🔐 Connexion à votre compte</h2>

    <?php if ($message): ?>
        <p class="form-message form-error"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" id="form-connexion" class="form-box">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" required>

        <label class="form-label">Mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-input" required>

        <button type="submit" class="btn form-button">Se connecter</button>
    </form>
</div>


    <script src="js/connexion.js" defer></script>
</body>
</html>


