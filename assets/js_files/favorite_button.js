document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".favoris").forEach(favBtn => {
    if (favBtn.disabled) return; // Skip disabled buttons

    favBtn.addEventListener("click", (e) => {
      e.preventDefault();
      const annonceId = favBtn.dataset.annonceId;

      fetch("/views/common_components/toggle_favorite.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `annonce_id=${encodeURIComponent(annonceId)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "added") {
          favBtn.textContent = "Supprimer des favoris";
          favBtn.style.backgroundColor = "#d9534f";
        } else if (data.status === "removed") {
          favBtn.textContent = "Ajouter aux favoris";
          favBtn.style.backgroundColor = "#bb9e1f";
        } else if (data.error) {
          alert(data.error);
        }
      })
      .catch(err => console.error("Erreur AJAX:", err));
    });
  });
});
