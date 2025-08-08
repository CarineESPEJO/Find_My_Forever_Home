<?php
session_start();

if (!isset($pdo)) {
    require_once("../common_components/pdo_connexion.php");
}

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit($id)) {
    header("Location: /index.php");
    exit();
}

$stmt = "";
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
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css">
    <link rel="stylesheet" href="/assets/css_files/annonce_style.css">
    <script src="/assets/js_files/favorite_button.js" defer></script>
    <script src="/assets/js_files/delete_modal.js" defer></script>
</head>

<body>
    <?php require_once("../common_components/header.php"); ?>
    <main><img src="<?php echo $annonce['image_URL'] ?>">
        <h1><?php echo $annonce['title'] ?></h1>
        <p><?php echo $annonce['price'] ?><?php echo $annonce['transaction_type'] === 'Rent' ? '€/month' : '€' ?></p>
        <p><?php echo $annonce['city'] ?>, FRANCE</p>
        <p><?php echo $annonce['property_type'] ?></p>
        <p><?php echo $annonce['description'] ?></p>

        <?php
        $annonceId = $annonce['id'];
        include __DIR__ . '/../common_components/favorite_button.php';
        ?>

        <?php if (!empty($_SESSION["userRole"]) && ($_SESSION["userRole"] === "admin" || ($_SESSION["userRole"] === "agent" && $_SESSION["userId"] === $annonce['user_id']))): ?>
            <a class="contact" href="update_annonce.php?id=<?= urlencode($annonce['id']) ?>">Modifier</a>
            <button class="contact" id="deleteBtn">Supprimer</button>
        <?php endif; ?>

        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <p>Voulez-vous vraiment supprimer cette annonce ?</p>
                <div class="modal-actions">
                    <a href="delete_annonce.php?id=<?= urlencode($annonce['id']) ?>" class="btn-confirm">Oui</a>
                    <button id="cancelBtn" class="btn-cancel">Non</button>
                </div>
            </div>
        </div>

    </main>
    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>