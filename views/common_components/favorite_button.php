<?php

if (!isset($annonceId) || !isset($pdo)) {
    throw new Exception("favorite_button.php requires \$annonceId and \$pdo");
}

$userId = $_SESSION['userId'] ?? null;

if (!$userId) {

    echo '<button class="favoris" disabled>Connexion requise</button>';
    return;
}


$stmt = $pdo->prepare('SELECT 1 FROM favorite WHERE user_id = :user_id AND listing_id = :listing_id LIMIT 1');
$stmt->execute([':user_id' => $userId, ':listing_id' => $annonceId]);
$isFavorited = (bool) $stmt->fetchColumn();
?>

<button class="favoris <?= $isFavorited ? 'favorited' : '' ?>"
    data-annonce-id="<?= htmlspecialchars($annonceId) ?>"
    type="button"
    style="background-color: <?= $isFavorited ? '#d9534f' : '#bb9e1f' ?>; color: white; padding: 0.4rem 1rem;
    border-radius: 0.25rem; font-weight: bold;">
    <?= $isFavorited ? 'Supprimer des favoris' : 'Ajouter aux favoris' ?>
</button>