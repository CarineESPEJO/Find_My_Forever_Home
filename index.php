<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find my dream home</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css">
    <link rel="stylesheet" href="/assets/css_files/index_style.css">
</head>

<body>
    <?php require_once("views/common_components/header.php"); ?>

    <main>
        <section>
            <h2>
                Nos annonces de maisons
            </h2>
            <hr>
            <div>
                <?php
                require_once('database/annonces_databases.php');
                foreach ($maisons as $annonce) {
                    include("views/common_components/vignette_view.php");
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
                    include("views/common_components/vignette_view.php");
                }
                ?>
            </div>
        </section>


    </main>

    <?php require_once("views/common_components/footer.php"); ?>
</body>

</html>