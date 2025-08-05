<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find my dream home</title>
    <link rel="stylesheet" href="/global_style.css">
    <link rel="stylesheet" href="/index_style.css">
</head>

<body>
    <?php require_once("views/common_views/header.php"); ?>

    <main>
        <section>
            <h2>
                Nos annonces de maisons
            </h2>
            <hr>
            <div>
                <?php
                require_once 'database/annonces_databases.php';
                foreach ($maisons as $annonce) {
                    include "views/common_views/vignette_view.php";
                }
                ?>
            </div>
        </section>

        <section>
            <h2>
                Nos annonces d'appartements
            </h2>
            <hr>
            <div>
            <?php foreach ($appartements as $annonce) {
                include("views/common_views/vignette_view.php");
            }
            ?>
            </div>
        </section>


    </main>

    <?php require_once("views/common_views/footer.php"); ?>
</body>

</html>