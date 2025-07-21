<?php
include_once '../config.php';

if (isset($_GET['id'], $_GET['action'])) {
    $id_avis = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'valider') {
        $pdo->prepare("UPDATE avis SET valide = 1 WHERE id_avis = ?")->execute([$id_avis]);
    } elseif ($action === 'refuser') {
        $pdo->prepare("DELETE FROM avis WHERE id_avis = ?")->execute([$id_avis]);
    }
}

header("Location: avis_en_attente.php");
exit;

