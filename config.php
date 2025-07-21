
<?php
$host = "x3zt8d54gaa7on6s.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$user = "ilpkwbx5vpfcnhlxd";
$password = "o4cz7nlx21emghfu3";
$dbname = "cb45a9wuvkj4mwv0";

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
