<?php
session_start();
$message = "";

// Connexion à la base de données
include_once 'config.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $mot_de_passe = $_POST["mot_de_passe"];

    // Recherche de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur["mot_de_passe"])) {
        $_SESSION["pseudo"] = $utilisateur["pseudo"];
        $_SESSION["role"] = $utilisateur["role"];
        $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];

        // Redirection selon le rôle
        if (!empty($_SESSION['redirect_after_login'])) {
            $redirect = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        }

        if ($utilisateur["role"] === "conducteur") {
            header("Location: conducteur/espace_conducteur.php");
            exit;
        } elseif ($utilisateur["role"] === "employe") {
            header("Location: employes/accueil_employe.php");
            exit;
        } elseif ($utilisateur["role"] === "admin") {
            header("Location: admin/accueil_admin.php");
            exit;
        } else {
            header("Location: utilisateur/espace_utilisateur.php");
            exit;
        }
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}

// Enregistre la page d’origine si quelqu’un tente d’accéder à une page protégée
if (!isset($_SESSION['id_utilisateur']) && empty($_SESSION['redirect_after_login'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - EcoRide</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="body-connexion">
<div class="connexion-wrapper">
  <h2 class="form-title"><i class="fas fa-user"></i>Connexion à votre compte</h2>

  <div class="form-container">
      <?php if (!empty($message)) : ?>
          <p class="form-message form-error"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>

      <form method="POST" id="form-connexion" class="form-box">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input" required>

          <label class="form-label">Mot de passe</label>
          <input type="password" name="mot_de_passe" class="form-input" required>

          <button type="submit" class="form-button">Se connecter</button>
      </form>
  </div>

  <!-- Bouton retour à l'accueil -->
  <div class="text-center mt-3">
      <a href="index.php" class="btn btn-secondary">
          <i class="fas fa-home"></i> Retour à l'accueil
      </a>
  </div>
</div>
    <script src="js/connexion.js" defer></script>
</body>
</html>
