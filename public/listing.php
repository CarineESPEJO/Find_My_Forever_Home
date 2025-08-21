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

$annonce = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$annonce) {
    header("HTTP/1.0 404 Not Found");
    echo "Annonce introuvable.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($annonce['title']) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/header_footer.css">
    <link rel="stylesheet" href="/assets/css/listing.css">
    <script src="/assets/js/favoriteButton.js" defer></script>
    <script src="/assets/js/deleteModal.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <main>
        <img src="<?= htmlspecialchars($annonce['image_URL']) ?>" alt="Image de l'annonce">
        <h1><?= htmlspecialchars($annonce['title']) ?></h1>
        <p><?= htmlspecialchars($annonce['price']) ?><?= $annonce['transaction_type'] === 'Rent' ? '€/month' : '€' ?></p>
        <p><?= htmlspecialchars($annonce['city']) ?>, FRANCE</p>
        <p><?= htmlspecialchars($annonce['property_type']) ?></p>
        <p><?= htmlspecialchars($annonce['description']) ?></p>

        <?php
        $annonceId = $annonce['id'];
        $isFavorited = isset($_SESSION['favorites']) && in_array($annonceId, $_SESSION['favorites']);
        include __DIR__ . '/../app/Views/Components/Buttons/FavoriteButton.php';
        ?>

        <?php if (!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || ($_SESSION["userRole"] === "agent" && $_SESSION["userId"] === $annonce['user_id']))): ?>
            <a class="contact" href="/formsLayout.php?form=UpdateListing&id=<?= (int)$annonce['id'] ?>">Modifier</a>
            <button class="contact" id="deleteBtn">Supprimer</button>
        <?php endif; ?>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <p>Voulez-vous vraiment supprimer cette annonce ?</p>
                <div class="modal-actions">
                    <a href="/DeleteListing.php?id=<?= urlencode($annonce['id']) ?>" class="btn-confirm">Oui</a>
                    <button id="cancelBtn" class="btn-cancel">Non</button>
                </div>
            </div>
        </div>
    </main>
    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>

</html>