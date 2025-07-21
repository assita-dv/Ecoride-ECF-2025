<?php
$db = parse_url(getenv("JAWSDB_URL"));

$host = $db["host"];
$user = $db["user"];
$pass = $db["pass"];
$db_name = ltrim($db["path"], "/");

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>