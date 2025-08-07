<article>
    <img src="<?php echo $annonce['image_URL']; ?>">
    <div>
    <h3 class="title"><?php echo $annonce['title']; ?></h3>
    <h4>
        <?php
        echo $annonce['price'];
        if ($annonce['transaction_type'] == 'Rent') {
            echo '€/ month';
        } elseif ($annonce['transaction_type'] == 'Sale') {
            echo '€';
        } ?>
    </h4>
    <p class="capitalize"><?php echo $annonce['city']; ?>, FRANCE</p>
    <p class="description"><?php echo $annonce['description']; ?></p>
    </p>
    <a class="contact" href="https://m.media-amazon.com/images/I/813kqvYoRfL.png">Contact</a>
    </div>
</article>