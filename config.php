
<?php
$host = "x3ztd854gaa7on6s.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$user = "i1pkw5txpfcxhlxd";
$password = "o4c7inx2lzmghfu3";
$dbname = "cb45a9wuvkj4mwn0";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
