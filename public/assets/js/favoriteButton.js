document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.favoris').forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      const listingId = button.dataset.annonceId; // match FavoriteButton.php

      try {
        const response = await fetch('/ToggleFavorite.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `annonce_id=${listingId}`
        });


        const result = await response.json();

        if (result.status === 'added') {
          button.classList.add('favorited');
          button.textContent = 'Supprimer des favoris';
        } else if (result.status === 'removed') {
          button.classList.remove('favorited');
          button.textContent = 'Ajouter aux favoris';
        } else if (result.error) {
          alert('Erreur serveur: ' + result.error);
        }
      } catch (err) {
        console.error(err);
        alert('Erreur réseau ou serveur.');
      }
    });
  });
});
