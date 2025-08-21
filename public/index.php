<?php
session_start();
if (!isset($_SESSION["userId"])) {
    $_SESSION["userId"] = null;
}

// Load the Database class
require_once __DIR__ . '/../app/Core/Database.php';

// Use fully qualified namespace
$pdo = \App\Core\Database::getConnection();

// -------------------- Step 1: Fetch favorites --------------------
$userId = $_SESSION['userId'];
$favorites = [];

if ($userId) {
    $stmt = $pdo->prepare("SELECT listing_id FROM favorite WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN); // array of listing IDs
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find my dream home</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/header_footer.css">
    <link rel="stylesheet" href="/assets/css/catalog.css">
    <script src="/assets/js/favoriteButton.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <main>
        <section>
            <h2>Nos annonces de maisons</h2>
            <hr>
            <div>
                <?php
                $stmt_is_house = true;
                require __DIR__ . '/../app/Queries/home_listings_queries.php';;
                foreach ($result as $annonce) {
                    include __DIR__ . '/../app/Views/Components/VignetteView.php';
                }
                ?>
            </div>
        </section>

        <section>
            <h2>Nos annonces d'appartements</h2>
            <hr>
            <div>
                <?php
                $stmt_is_house = false;
                require __DIR__ . '/../app/Queries/home_listings_queries.php';;
                foreach ($result as $annonce) {
                    include __DIR__ . '/../app/Views/Components/VignetteView.php';
                }
                ?>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>
</body>

</html>