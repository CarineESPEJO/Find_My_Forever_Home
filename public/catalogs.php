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

// Handle AJAX request & offset
$ajaxRequest = $_GET['ajax'] ?? false;
$offset = (int)($_GET['offset'] ?? $_SESSION['nb_offset'] ?? 0);

// --- Handle pagination POST ---
if (!$ajaxRequest) {
    if (!isset($_SESSION['nb_offset'])) $_SESSION['nb_offset'] = 0;
    $currentPage = $_SERVER['SCRIPT_NAME'];
    if (!isset($_SESSION['last_visited_page']) || $_SESSION['last_visited_page'] !== $currentPage . $propertyType) {
        $_SESSION['nb_offset'] = 0;
    }
    $_SESSION['last_visited_page'] = $currentPage . $propertyType;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['increase'])) $_SESSION['nb_offset'] += 12;
        if (isset($_POST['decrease'])) $_SESSION['nb_offset'] -= 12;
        if ($_SESSION['nb_offset'] < 0) $_SESSION['nb_offset'] = 0;
    }
    $offset = $_SESSION['nb_offset'];
}

// --- Fetch user ID ---
$userId = $_SESSION['userId'] ?? null;

// --- Fetch user's favorites IDs for marking ---
$favorites = [];
if ($userId) {
    $stmt = $pdo->prepare('SELECT listing_id FROM favorite WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// --- Fetch listings ---
if ($propertyType === 'Favorite' && $userId) {
    $stmt = $pdo->prepare("
        SELECT l.*, pt.name AS property_type, tt.name AS transaction_type
        FROM favorite f
        JOIN listing l ON f.listing_id = l.id
        JOIN propertytype pt ON l.property_type_id = pt.id
        JOIN transactiontype tt ON l.transaction_type_id = tt.id
        WHERE f.user_id = :user_id
        ORDER BY f.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', 12, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle AJAX request: return only vignette HTML
    if ($ajaxRequest) {
        foreach ($listings as $annonce) {
            $isFavorited = in_array($annonce['id'], $favorites);
            include __DIR__ . '/../app/Views/Components/VignetteView.php';
        }
        exit;
    }

    // Total count for pagination
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $userId]);
    $totalCount = (int)$stmt->fetchColumn();
} else {
    // Fetch by type with pagination
    $listings = $repo->getByTypeWithOffset($propertyType, $offset);
    $totalCount = $repo->countByType($propertyType);
}
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
</head>

<body>
    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <main>
        <section>
            <h2>Nos annonces de <?= htmlspecialchars($propertyType) ?></h2>
            <hr>
            <div>
                <?php foreach ($listings as $annonce): ?>
                    <?php
                    $isFavorited = in_array($annonce['id'], $favorites);
                    include __DIR__ . '/../app/Views/Components/VignetteView.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (!$ajaxRequest && $totalCount > 12): // Show pagination only if more than 12 
        ?>
            <form method="POST">
                <button type="submit" name="decrease" <?= $offset == 0 ? 'disabled' : '' ?>>Page précédente</button>
                <button type="submit" name="increase" <?= ($offset + 12) >= $totalCount ? 'disabled' : '' ?>>Page suivante</button>
            </form>
        <?php endif; ?>
    </main>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>
</body>

</html>