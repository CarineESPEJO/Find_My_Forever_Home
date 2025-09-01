<?php
session_start();

require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

$pdo = Database::getConnection();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit($id)) {
    header("Location: /index.php");
    exit();
}

$stmt = $pdo->prepare(
    'SELECT lis.id AS id, image_URL, title, price, 
            protyp.name AS property_type, 
            tratyp.name AS transaction_type, 
            city, description, user_id,
            lis.created_at, lis.updated_at
     FROM listing AS lis
     JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
     JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
     WHERE lis.id = :id
     LIMIT 1;'
);

$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();

$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    header("HTTP/1.0 404 Not Found");
    echo "Annonce introuvable.";
    exit();
}

$userId = $_SESSION['userId'] ?? null;
$listingId = $listing['id'];
$favorites = $_SESSION['favorites'] ?? [];
$isFavorited = $userId ? in_array($listingId, $favorites) : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($listing['title']) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/header_footer.css">
    <link rel="stylesheet" href="/assets/css/listing.css">
    <script src="/assets/js/favoriteButton.js" defer></script>
    <script src="/assets/js/deleteModal.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <main class="listing-container">
        <!-- Image -->
        <div class="listing-image">
            <img src="<?= htmlspecialchars($listing['image_URL']) ?>" alt="Image de l'annonce">
        </div>

        <!-- Info -->
        <div class="listing-info">
            <h1><?= htmlspecialchars($listing['title']) ?></h1>
            <p class="price"><?= htmlspecialchars($listing['price']) ?><?= $listing['transaction_type'] === 'Rent' ? ' €/mois' : ' €' ?></p>
            <p class="location"><?= htmlspecialchars($listing['city']) ?>, FRANCE</p>
            <p class="type"><?= htmlspecialchars($listing['property_type']) ?></p>

            <!-- Buttons -->
            <div class="listing-actions">
                <?php include __DIR__ . '/../app/Views/Components/Buttons/FavoriteButton.php'; ?>
                <?php if (!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || ($_SESSION["userRole"] === "agent" && $_SESSION["userId"] === $listing['user_id']))): ?>
                    <a class="contact" href="/formsLayout.php?form=UpdateListing&id=<?= (int)$listing['id'] ?>">Modifier</a>
                    <button class="contact" id="deleteBtn">Supprimer</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Description -->
        <div class="listing-description">
            <h2>Description</h2>
            <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <p>Voulez-vous vraiment supprimer cette annonce ?</p>
                <div class="modal-actions">
                    <a href="/DeleteListing.php?id=<?= urlencode($listing['id']) ?>" class="btn-confirm">Oui</a>
                    <button id="cancelBtn" class="btn-cancel">Non</button>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>
</body>

</html>