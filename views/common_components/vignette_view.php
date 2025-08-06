<article>
    <img src="<?php echo $annonce['image_url']; ?>">
    <div>
    <h3><?php echo $annonce['titre']; ?></h3>
    <h4>
        <?php
        echo $annonce['prix'];
        if ($annonce['type'] == 'Rent') {
            echo '€/ month';
        } elseif ($annonce['type'] == 'Sale') {
            echo '€';
        } ?>
    </h4>
    <p><?php echo $annonce['localisation']; ?>, <?php echo $annonce['pays']; ?></p>
    <p class="description"><?php echo $annonce['description']; ?></p>
    </p>
    <a class="contact" href="https://m.media-amazon.com/images/I/813kqvYoRfL.png">Contact</a>
    </div>
</article>