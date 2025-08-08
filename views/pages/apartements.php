<?php
session_start();
require_once("../common_components/pdo_connexion.php");

$stmt = "";


$stmt = $pdo->prepare(
    'SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type, tratyp.name AS transaction_type, city, description, lis.created_at AS created_at,lis.updated_at AS updated_at 
         FROM listing AS lis
         JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
         JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
         WHERE protyp.name = "Apartment"
         ORDER BY lis.id DESC;'
);

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css">
    <link rel="stylesheet" href="/assets/css_files/index_style.css">
    <script src="/assets/js_files/favorite_button.js" defer></script>
</head>

<body>
    <?php require_once("../common_components/header.php"); ?>

    <main>
        <section>
            <h2>Vos favoris</h2>
            <hr>
            <div>
                <?php
                $stmt_is_house = true;
                foreach ($result as $annonce) {
                    include("../common_components/vignette_view.php");
                }
                ?>
            </div>
        </section>
    </main>

    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>