

function toggleConducteur(isConducteur) {
    const conducteurFields = document.querySelector('.conducteur-fields');
    if (conducteurFields) {
        conducteurFields.style.display = isConducteur ? 'block' : 'none';
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    const roleRadios = document.querySelectorAll('input[name="role"]');

    // Initialiser l'affichage selon le bouton sélectionné par défaut
    const isConducteur = document.querySelector('input[name="role"]:checked').value === 'conducteur';
    toggleConducteur(isConducteur);

    // Écouteurs de changement de rôle
    roleRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            toggleConducteur(radio.value === 'conducteur');
        });
    });
});
