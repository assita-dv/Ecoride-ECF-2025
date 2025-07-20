<?php
session_start();
if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'employe') {
    header("Location: ../connexion.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=ecoride_db;charset=utf8', 'root', '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_avis = $_POST['id_avis'];
    $action = $_POST['action'];

    if ($action === 'valider') {
        $pdo->prepare("UPDATE avis SET valide = 1 WHERE id_avis = ?")->execute([$id_avis]);
    } elseif ($action === 'refuser') {
        $pdo->prepare("DELETE FROM avis WHERE id_avis = ?")->execute([$id_avis]);
    }
}

header("Location: espace_employe.php");
exit;
