<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../connexion.php");
    exit;
}

$id_utilisateur = $_SESSION['id_utilisateur'];
$id_covoiturage = $_POST['id_covoiturage'] ?? null;
$validation = $_POST['validation'] ?? null;
$commentaire = $_POST['commentaire'] ?? null;

if (!$id_covoiturage || !$validation || !in_array($validation, ['oui', 'non'])) {
    header("Location: espace_utilisateur.php?status=erreur_validation");
    exit;
}

$statut = $validation === 'oui' ? 'valide' : 'probleme';

$stmt = $pdo->prepare("UPDATE participe SET colonne_validation = ?, commentaire_probleme = ?, statut_validation = ? WHERE id_utilisateur = ? AND id_covoiturage = ?");
$stmt->execute([$validation, $commentaire, $statut, $id_utilisateur, $id_covoiturage]);

header("Location: espace_utilisateur.php?status=validation_ok");
exit;
?>
