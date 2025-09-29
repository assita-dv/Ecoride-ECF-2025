<?php
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/config.php';

// 1) Récup des filtres
$ville_depart  = trim($_GET['ville_depart']  ?? '');
$ville_arrivee = trim($_GET['ville_arrivee'] ?? '');
$date_depart   = trim($_GET['date_depart']   ?? '');
$heure_depart  = trim($_GET['heure_depart']  ?? '');

// Convertit JJ/MM/AAAA -> YYYY-MM-DD si besoin
if (preg_match('#^\d{2}/\d{2}/\d{4}$#', $date_depart)) {
  [$j,$m,$a] = explode('/', $date_depart);
  $date_depart = "$a-$m-$j";
}

// 2) Même requête que ta page, sécurisée
$sql = "SELECT c.*, u.nom, u.prenom, u.pseudo, u.email, u.photo, c.id_utilisateur 
        FROM covoiturage c
        JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
        WHERE 1";
$params = [];

if ($ville_depart !== '') {
  $sql .= " AND LOWER(c.ville_depart) = LOWER(:ville_depart)";
  $params[':ville_depart'] = $ville_depart;
}
if ($ville_arrivee !== '') {
  $sql .= " AND LOWER(c.ville_arrivee) = LOWER(:ville_arrivee)";
  $params[':ville_arrivee'] = $ville_arrivee;
}
if ($date_depart !== '') {
  $sql .= " AND c.date_depart = :date_depart";
  $params[':date_depart'] = $date_depart;
}
if ($heure_depart !== '') {
  // format HH:MM attendu ; on compare sur HH:MM
  $sql .= " AND TIME_FORMAT(c.heure_depart, '%H:%i') = :heure_depart";
  $params[':heure_depart'] = $heure_depart;
}

$sql .= " ORDER BY c.date_depart ASC, c.heure_depart ASC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3) On réutilise EXACTEMENT le même rendu des cartes
ob_start();
require __DIR__ . '/partials/trajets_list.php';
$html = ob_get_clean();

echo $html;
