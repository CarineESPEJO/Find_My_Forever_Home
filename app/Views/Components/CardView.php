<!-- card for the listings in index and catalogs -->
<article class="card">
    <a class="card-link" href="/listing.php?id=<?= (int)$listing['id'] ?>">
        <!-- ?= is to say ?php echo and htmlspecialchars() escapes special HTML characters, preventing XSS attacks -->
        <img src="<?= htmlspecialchars($listing['image_URL']) ?>" alt="Image de l'annonce" />
        <div>
            <h3 class="title"><?= htmlspecialchars($listing['title']) ?></h3>
            <h4>
                <?= htmlspecialchars($listing['price']) ?>
                <?= $listing['transaction_type'] === 'Rent' ? '€/mois' : '€' ?>
            </h4>
            <p class="capitalize"><?= htmlspecialchars($listing['city']) ?>, FRANCE</p>
            <p class="description"><?= htmlspecialchars($listing['description']) ?></p>
            <p class="property_type"><?= htmlspecialchars($listing['property_type'] === 'House' ? 'Maison' : 'Appartement') ?></p>
        </div>
    </a>

    <!-- Favorite button -->
    <?php
    $listingId = $listing['id'];
    $isFavorited = $userId ? in_array($listingId, $favorites) : false;
    include __DIR__ . '/Buttons/FavoriteButton.php';
    ?>

    <a class="contact contact-btn" href="">Contact</a>
</article>