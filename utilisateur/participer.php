<?php
session_start();

// Connexion à la base de données
include_once '../config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Vérifie que l’ID du covoiturage est présent via POST
if (!isset($_POST['id_covoiturage']) || empty($_POST['id_covoiturage'])) {
    header("Location: index.php");
    exit;
}

$id_covoiturage = (int) $_POST['id_covoiturage'];

// Sécurité : vérifier que l'ID est un entier positif
if ($id_covoiturage <= 0) {
    header("Location: confirmation_participation.php?status=erreur");
    exit;
}

// 1. Vérifie si l'utilisateur a déjà participé
$check = $pdo->prepare("SELECT * FROM participe WHERE id_utilisateur = ? AND id_covoiturage = ?");
$check->execute([$id_utilisateur, $id_covoiturage]);

if ($check->rowCount() > 0) {
    header("Location: confirmation_participation.php?status=deja_participant");
    exit;
}

// 2. Vérifie les crédits
$stmt_credit = $pdo->prepare("SELECT credits FROM utilisateur WHERE id_utilisateur = ?");
$stmt_credit->execute([$id_utilisateur]);
$credit_user = $stmt_credit->fetchColumn();

// 3. Récupère les infos du trajet (prix + places restantes)
$stmt_trajet = $pdo->prepare("SELECT prix, nb_place, id_utilisateur AS id_conducteur FROM covoiturage WHERE id_covoiturage = ?");
$stmt_trajet->execute([$id_covoiturage]);
$trajet = $stmt_trajet->fetch();

if (!$trajet || $trajet['nb_place'] < 1) {
    header("Location: confirmation_participation.php?status=erreur");
    exit;
}

$prix_trajet = (int) $trajet['prix'];

// 4. Vérifie si l'utilisateur a assez de crédits
if ($credit_user < $prix_trajet) {
    header("Location: confirmation_participation.php?status=pasassezdecredits");
    exit;
}

// 5. Déduction des crédits
$update_credit = $pdo->prepare("UPDATE utilisateur SET credits = credits - ? WHERE id_utilisateur = ?");
$update_credit->execute([$prix_trajet, $id_utilisateur]);

// 6. Insertion dans la table participation
$insert = $pdo->prepare("INSERT INTO participe (id_utilisateur, id_covoiturage) VALUES (?, ?)");
$insert->execute([$id_utilisateur, $id_covoiturage]);

// 7. Mise à jour du nombre de places restantes
$update_places = $pdo->prepare("UPDATE covoiturage SET nb_place = nb_place - 1 WHERE id_covoiturage = ?");
$update_places->execute([$id_covoiturage]);

// 8. Redirection finale
header("Location: confirmation_participation.php?status=ok");
exit;
?>



