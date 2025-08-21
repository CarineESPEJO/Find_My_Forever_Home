<?php
$userId = $_SESSION['userId'] ?? null;

if (!$userId) {
    echo '<button class="favoris" disabled>Connexion requise</button>';
    return;
}
?>

<button
    class="favoris <?= $isFavorited ? 'favorited' : '' ?>"
    data-annonce-id="<?= $annonceId ?>">
    <?= $isFavorited ? 'Supprimer des favoris' : 'Ajouter aux favoris' ?>
</button>