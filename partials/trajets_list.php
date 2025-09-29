<?php if (count($resultats) > 0): ?>
  <?php foreach ($resultats as $trajet): ?>
    <div class="card mb-4 shadow-sm rounded-4 p-3">
      <div class="row align-items-center">

        <div class="col-md-8">
          <div class="d-flex align-items-center mb-2">
            <div class="me-3">
              <strong><?= htmlspecialchars($trajet['heure_depart']) ?></strong>
            </div>
            <div class="text-muted small">Durée estimée : 2h00</div>
          </div>
          <div class="fw-bold fs-6">
            <?= htmlspecialchars($trajet['ville_depart']) ?>
            <span class="ligne-trajet mx-2">
              <span class="rond"></span><span class="trait"></span><span class="rond"></span>
            </span>
            <?= htmlspecialchars($trajet['ville_arrivee']) ?>
          </div>
        </div>

        <div class="col-md-4 text-md-end text-center">
          <div class="fs-5 fw-bold text-success text-start text-md-end">
            <?= htmlspecialchars($trajet['prix']) ?> €
          </div>
        </div>
      </div>

      <hr class="my-3">

      <div class="row align-items-center">
        <div class="col-auto">
          <img src="<?= !empty($trajet['photo']) ? 'uploads/' . htmlspecialchars($trajet['photo']) : 'images/default_avatar.png' ?>"
               alt="photo conducteur" class="rounded-circle me-2" width="50" height="50">
        </div>
        <div class="col">
          <strong><?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars($trajet['nom']) ?></strong>
          <div class="text-muted small">@<?= htmlspecialchars($trajet['pseudo']) ?></div>
          <div class="text-success small mt-1">
            <i class="fas fa-car text-success me-1"></i> Voyage écologique
          </div>
        </div>
        <div class="col-md-4 text-md-end text-center">
          <a href="/conducteur/05_profil_conducteur.php?id=<?= $trajet['id_utilisateur'] ?>"
             class="btn btn-outline-success btn-sm d-block d-md-inline-block mt-2 mb-2 mb-md-0">
            Voir le profil
          </a>
          <a href="/utilisateur/confirmer_participation_etape.php?id=<?= $trajet['id_covoiturage'] ?>"
             class="btn btn-success d-block d-md-inline-block mt-2">
            Participer
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="alert alert-warning mt-4">Aucun trajet trouvé.</div>
<?php endif; ?>
