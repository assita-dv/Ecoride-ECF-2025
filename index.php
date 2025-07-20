<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - EcoRide</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

  <?php include('page/header.php'); ?>


<section class="hero">
  <div class="hero-text">
    <h1>Bienvenue sur EcoRide </h1>
    <h2>Le covoiturage pour un avenir plus vert</h2>
    <p>
      EcoRide s'engage à réduire l’impact environnemental des déplacements<br>
      en mettant en relation conducteurs et passagers pour des trajets partagés.<br>
      Cela permet de diminuer les émissions de carbone<br>
      et de promouvoir un transport durable.
    </p>
  </div>
  <div class="hero-img">
    <img src="images-ecoride/bienvenue.png" alt="Illustration EcoRide">
  </div>
</section>

<section class="recherche">
  <h3>Rechercher un itinéraire</h3>
  
  <form action="04_resultats.php" method="get">
    <input type="text" name="ville_depart" placeholder="Départ">
    <input type="text" name="ville_arrivee" placeholder="Destination">
    <input type="time" name="heure_depart"  placeholder="Heure">
    <input type="date" name="date_depart" >
    <button type="submit">Rechercher</button>
</form>

</section>
<div class="footer-img">
    <img src="images-ecoride/arbrevert.png" alt="Illustration EcoRide">
  </div>
  <?php include('page/footer.php'); ?>

</body>
</html>

