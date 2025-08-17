<?php
/*$db = parse_url(getenv("JAWSDB_URL"));

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
*/


$jawsdb_url = getenv("JAWSDB_URL");

if ($jawsdb_url) {
    // En ligne
    $db = parse_url($jawsdb_url);
    $host = $db["host"];
    $user = $db["user"];
    $pass = $db["pass"];
    $db_name = ltrim($db["path"], "/");
} else {
    // En local
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "ecoride_db";
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
