<?php
//start the session or resume the on already running that the php pages transferred common elements (sessions var)
session_start();
//so that the header parts and some other components works even when we aren't logged in and so userId doesn't exist
if (!isset($_SESSION["userId"])) {
    $_SESSION["userId"] = null;
}

// Load the Database file to connect to the database FMDH
require_once __DIR__ . '/../app/Core/Database.php';
// Call the class Database and save in pdo
$pdo = \App\Core\Database::getConnection();

// Fetch Favorites
//if user connected (userId not null), ask (query) for  the favorites of the user
//to use ON the listings to adapt the favorite button
//its needed here because we call the CardView and it needs to know if the listing is inthe favorites or not

// We could have put immediately $_SESSION['userId'] in the if condition and in execute
// but its cleaner to put it in a var in case we need it somewhere else in the file
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
                // call the query of the last 3 rows of the listings which are homes
                require __DIR__ . '/../app/Queries/home_listings_queries.php';;
                // for each, create a card with a link to the listing page corresponding
                foreach ($result as $listing) {
                    include __DIR__ . '/../app/Views/Components/CardView.php';
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
                // call the query of the last 3 rows of the listings which are apartments
                require __DIR__ . '/../app/Queries/home_listings_queries.php';;
                foreach ($result as $listing) {
                    include __DIR__ . '/../app/Views/Components/CardView.php';
                }
                ?>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>
</body>

</html> 