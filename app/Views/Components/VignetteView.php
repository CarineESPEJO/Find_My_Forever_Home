<article class="vignette">
    <a class="card-link" href="/listing.php?id=<?= (int)$annonce['id'] ?>">
        <img src="<?= htmlspecialchars($annonce['image_URL']) ?>" alt="Image de l'annonce" />
        <div>
            <h3 class="title"><?= htmlspecialchars($annonce['title']) ?></h3>
            <h4>
                <?= htmlspecialchars($annonce['price']) ?>
                <?= $annonce['transaction_type'] === 'Rent' ? '€/mois' : '€' ?>
            </h4>
            <p class="capitalize"><?= htmlspecialchars($annonce['city']) ?>, FRANCE</p>
            <p class="description"><?= htmlspecialchars($annonce['description']) ?></p>
            <p class="property_type"><?= htmlspecialchars($annonce['property_type'] === 'House' ? 'Maison' : 'Appartement') ?></p>
        </div>
    </a>

    <!-- Favorite button -->
    <?php
    $annonceId = $annonce['id'];
    $isFavorited = $userId ? in_array($annonceId, $favorites) : false;
    include __DIR__ . '/Buttons/FavoriteButton.php';
    ?>

    <a class="contact contact-btn" href="">Contact</a>
</article>