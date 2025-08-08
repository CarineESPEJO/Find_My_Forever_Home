<?php
session_start();
require_once("../common_components/pdo_connexion.php");

// Get current script path (e.g., "/views/pages/favorites.php")
$currentPage = $_SERVER['SCRIPT_NAME'];

// Check if last visited page is set and differs from current
if (!isset($_SESSION['last_visited_page']) || $_SESSION['last_visited_page'] !== $currentPage) {
    // Reset offset because we're coming from a different page
    $_SESSION['nb_offset'] = 0;
}

// Update last visited page to current
$_SESSION['last_visited_page'] = $currentPage;

// Ensure offset is initialized
if (!isset($_SESSION['nb_offset'])) {
    $_SESSION['nb_offset'] = 0;
}

// Handle pagination form POSTs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['increase'])) {
        $_SESSION['nb_offset'] += 12;
    } elseif (isset($_POST['decrease'])) {
        $_SESSION['nb_offset'] -= 12;
    }
    if ($_SESSION['nb_offset'] < 0) {
        $_SESSION['nb_offset'] = 0;
    }
}

// Get total rows count
$stmtCount = $pdo->prepare(
    'SELECT COUNT(*) FROM listing AS lis
     JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
     WHERE protyp.name = "Apartment"'
);
$stmtCount->execute();
$totalRows = (int) $stmtCount->fetchColumn();

// Clamp offset to max possible value
$maxOffset = max(0, $totalRows - 12);
if ($_SESSION['nb_offset'] > $maxOffset) {
    $_SESSION['nb_offset'] = $maxOffset;
}

// Prepare and execute main query with LIMIT and OFFSET
$stmt = $pdo->prepare(
    'SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type, tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
     FROM listing AS lis
     JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
     JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
     WHERE protyp.name = "House"
     ORDER BY lis.id DESC
     LIMIT 12 OFFSET :nb_offset'
);
$stmt->bindValue(':nb_offset', $_SESSION['nb_offset'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Favorites</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css" />
    <link rel="stylesheet" href="/assets/css_files/index_style.css" />
    <script src="/assets/js_files/favorite_button.js" defer></script>
</head>

<body>
    <?php require_once("../common_components/header.php"); ?>

    <main>
        <section>
            <h2>Vos favoris</h2>
            <hr />
            <div>
                <?php
                $stmt_is_house = true;
                foreach ($result as $annonce) {
                    include("../common_components/vignette_view.php");
                }
                ?>
            </div>
        </section>
        <form method="POST">
            <button type="submit" name="decrease" <?= $_SESSION['nb_offset'] == 0 ? 'disabled' : '' ?>>Page précédente</button>
            <button type="submit" name="increase" <?= ($_SESSION['nb_offset'] + 12) >= $totalRows ? 'disabled' : '' ?>>Page suivante</button>
        </form>
    </main>

    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>
