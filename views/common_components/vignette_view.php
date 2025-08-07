<article class="vignette">
    <a class="card-link" href="views/pages/annonce.php?id=<?= urlencode($annonce['id']) ?>">
        <img src="<?= htmlspecialchars($annonce['image_URL']) ?>" alt="Image de l'annonce" />
        <div>
            <h3 class="title"><?= htmlspecialchars($annonce['title']) ?></h3>
            <h4>
                <?= htmlspecialchars($annonce['price']) ?>
                <?= $annonce['transaction_type'] === 'Rent' ? '€/month' : '€' ?>
            </h4>
            <p class="capitalize"><?= htmlspecialchars($annonce['city']) ?>, FRANCE</p>
            <p class="description"><?= htmlspecialchars($annonce['description']) ?></p>
        </div>
    </a>

    <!-- outside of the main link to be also clickable but elsewhere -->
    <a class="contact contact-btn" href="/views/pages/contact.php?annonce_id=<?= urlencode($annonce['id']) ?>">Contact</a>
</article>
