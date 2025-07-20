<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=ecoride_db;charset=utf8", 'root', '');
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../connexion.php");
    exit;
}

if (isset($_POST['id_covoiturage'])) {
    $id_covoiturage = (int) $_POST['id_covoiturage'];
    $stmt = $pdo->prepare("UPDATE covoiturage SET statut = 'en_cours' WHERE id_covoiturage = ?");
    $stmt->execute([$id_covoiturage]);
}

header("Location: ../conducteur/espace_conducteur.php");
exit;
