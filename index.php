<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Accueil - EcoRide</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>

  <?php include('page/header.php'); ?>

</header>
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
<div id="searchResults" class="mt-4"></div>


  <div id="trajetsResult" class="mt-3"></div>
</section>

<div class="footer-img">
    <img src="images-ecoride/arbrevert.png" alt="Illustration EcoRide">
</div>

<?php include('page/footer.php'); ?>

<!-- Script AJAX -->

<script>
(() => {
  const form = document.querySelector('form[action="04_resultats.php"]');
  const box  = document.getElementById('searchResults');
  if (!form || !box) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault(); // empêcher la navigation
    const params = new URLSearchParams(new FormData(form));
    box.innerHTML = '<div class="text-muted">Recherche en cours…</div>';

    try {
      const resp = await fetch('api_search_trajets.php?' + params.toString(), {
        headers: { 'Accept': 'text/html' }
      });
      const html = await resp.text();
      box.innerHTML = html;
      // option : scroll jusque la zone
      box.scrollIntoView({behavior:'smooth', block:'start'});
    } catch (err) {
      box.innerHTML = '<p class="text-danger">Erreur de chargement.</p>';
    }
  });
})();
</script>

</body>
</html>
