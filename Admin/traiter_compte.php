<?php
session_start();

$host = 'localhost';
$dbname = 'ecoride_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'suspendre') {
        $stmt = $pdo->prepare("UPDATE utilisateur SET suspendu = 1 WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'supprimer') {
        $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
    }
}

header("Location: gerer_comptes.php");
exit;
