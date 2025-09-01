<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/ListingRepository.php';

use App\Models\ListingRepository;

$repo = new ListingRepository();
$pdo = \App\Core\Database::getConnection();

// Determine property type from URL
$propertyType = isset($_GET['type']) ? ucfirst(strtolower($_GET['type'])) : 'House';

// Determine page title based on type
switch ($propertyType) {
    case 'House':
        $pageTitle = "Nos annonces de Maisons";
        break;
    case 'Apartment':
        $pageTitle = "Nos annonces d'Appartements";
        break;
    case 'Search':
        $pageTitle = "Rechercher une annonce";
        break;
    case 'Favorite':
        $pageTitle = "Vos favoris";
        break;
    default:
        $pageTitle = "Nos annonces";
        break;
}

// Handle AJAX request & offset
$ajaxRequest = $_GET['ajax'] ?? false;
$offset = (int)($_GET['offset'] ?? $_SESSION['nb_offset'] ?? 0);

// Fetch user ID
$userId = $_SESSION['userId'] ?? null;

// Filters (only for Search and Favorites)
$filters = [];
if ($propertyType === 'Search' || $propertyType === 'Favorite') {
    $filters['city'] = $_GET['city'] ?? '';
    $filters['max_price'] = $_GET['max_price'] ?? '';
    $filters['property_type'] = $_GET['property_type'] ?? '';
    $filters['transaction_type'] = $_GET['transaction_type'] ?? '';
}

// Fetch total count
if ($propertyType === 'Favorite' && $userId) {
    $totalCount = $repo->countFavorites($userId, $filters);
} elseif ($propertyType === 'Search') {
    $totalCount = $repo->countSearch($filters);
} else {
    $totalCount = $repo->countByType($propertyType);
}

// Handle pagination POST
if (!$ajaxRequest) {
    if (!isset($_SESSION['nb_offset'])) $_SESSION['nb_offset'] = 0;
    $currentPage = $_SERVER['SCRIPT_NAME'];
    if (!isset($_SESSION['last_visited_page']) || $_SESSION['last_visited_page'] !== $currentPage . $propertyType) {
        $_SESSION['nb_offset'] = 0;
    }
    $_SESSION['last_visited_page'] = $currentPage . $propertyType;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['increase'])) {
            $_SESSION['nb_offset'] += 12;
        }
        if (isset($_POST['decrease'])) {
            $_SESSION['nb_offset'] -= 12;
        }
        if (isset($_POST['go']) && isset($_POST['goto_page'])) {
            $requestedPage = (int) $_POST['goto_page'];
            $maxPage = ceil($totalCount / 12);
            if ($requestedPage >= 1 && $requestedPage <= $maxPage) {
                $_SESSION['nb_offset'] = ($requestedPage - 1) * 12;
            }
        }
        if ($_SESSION['nb_offset'] < 0) $_SESSION['nb_offset'] = 0;
    }
    $offset = $_SESSION['nb_offset'];
}

// Fetch user's favorites IDs for marking
$favorites = [];
if ($userId) {
    $stmt = $pdo->prepare('SELECT listing_id FROM favorite WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch listings
if ($propertyType === 'Favorite' && $userId) {
    $listings = $repo->searchFavorites($userId, $filters, $offset);
} elseif ($propertyType === 'Search') {
    $listings = $repo->search($filters, $offset);
} else {
    $listings = $repo->getByTypeWithOffset($propertyType, $offset);
}

// Fetch dropdown values
$propertyTypes = $repo->getPropertyTypes();
$transactionTypes = $repo->getTransactionTypes();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($propertyType) ?> Listings</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/header_footer.css">
    <link rel="stylesheet" href="/assets/css/catalog.css">
    <script src="/assets/js/favoriteButton.js" defer></script>
    <script src="/assets/js/pagination.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <main>
        <section>
            <h2><?= htmlspecialchars($pageTitle) ?></h2>
            <hr>

            <!-- Search form (only for Search & Favorites) -->
            <?php if ($propertyType === 'Search' || $propertyType === 'Favorite'): ?>
                <form method="GET" style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">
                    <input type="hidden" name="type" value="<?= htmlspecialchars($propertyType) ?>">

                    <input type="text" name="city" placeholder="Ville"
                        value="<?= htmlspecialchars($filters['city']) ?>">

                    <input type="number" name="max_price" placeholder="Prix max"
                        value="<?= htmlspecialchars($filters['max_price']) ?>">

                    <select name="property_type">
                        <option value="">Type de bien</option>
                        <?php foreach ($propertyTypes as $pt): ?>
                            <option value="<?= $pt['id'] ?>" <?= $filters['property_type'] == $pt['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pt['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="transaction_type">
                        <option value="">Type de transaction</option>
                        <?php foreach ($transactionTypes as $tt): ?>
                            <option value="<?= $tt['id'] ?>" <?= $filters['transaction_type'] == $tt['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tt['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Rechercher</button>
                </form>
            <?php endif; ?>

            <div>
                <?php foreach ($listings as $listing): ?>
                    <?php
                    $isFavorited = in_array($listing['id'], $favorites);
                    include __DIR__ . '/../app/Views/Components/CardView.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!$ajaxRequest && $totalCount > 12): ?>
            <form method="POST" style="margin-top:20px; display:flex; gap:10px; align-items:center;">
                <button type="submit" name="decrease" <?= $offset == 0 ? 'disabled' : '' ?>>Page précédente</button>

                <span>
                    Page <?= floor($offset / 12) + 1 ?> / <?= ceil($totalCount / 12) ?>
                </span>

                <input
                    type="number"
                    name="goto_page"
                    min="1"
                    max="<?= ceil($totalCount / 12) ?>"
                    value="<?= floor($offset / 12) + 1 ?>"
                    style="width:60px; text-align:center;">
                <button type="submit" name="go">Aller</button>

                <button type="submit" name="increase" <?= ($offset + 12) >= $totalCount ? 'disabled' : '' ?>>Page suivante</button>
            </form>
        <?php endif; ?>
    </main>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>
</body>

</html>