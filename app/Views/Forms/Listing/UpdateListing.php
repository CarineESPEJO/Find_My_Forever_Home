<?php

// Access control: only logged-in admin or agent
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: formsLayout.php?form=login");
    exit();
}

if ($_SESSION["userRole"] !== "admin" && $_SESSION["userRole"] !== "agent") {
    header("Location: ../../index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id || !ctype_digit($id)) {
    header("Location: ../../index.php");
    exit();
}

// Fetch the listing
$stmt = $pdo->prepare(
    'SELECT title, description, price, city, image_URL, property_type_id, transaction_type_id, user_id 
     FROM listing WHERE id = :id LIMIT 1'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    header("Location: ../../index.php");
    exit();
}

// Agent can only update their own listing
if ($_SESSION["userRole"] === "agent" && $_SESSION["userId"] !== $listing['user_id']) {
    header("Location: ../../index.php");
    exit();
}

// Initialize form values
$title = $listing['title'];
$price = $listing['price'];
$city = $listing['city'];
$description = $listing['description'];
$imagePath = $listing['image_URL'];
$property_type = $listing['property_type_id'] == 1 ? "House" : "Apartment";
$transaction_type = $listing['transaction_type_id'] == 1 ? "Rent" : "Sale";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = $_POST['price'] ?? '';
    $city = strtolower(trim($_POST['city'] ?? ''));
    $description = trim($_POST['description'] ?? '');
    $property_type = $_POST['property_type'] ?? '';
    $transaction_type = $_POST['transaction_type'] ?? '';
    $user_id = $_SESSION['userId'] ?? null;

    $cityName_pattern = '/^[\p{L}\p{M}\s\'\.\-ʻ’]{1,150}$/u';

    // Validation
    if (strlen($title) < 5 || strlen($title) > 255) {
        $errors['title'] = "Le titre doit être compris entre 5 et 255 caractères";
    }

    if (!ctype_digit($price) || intval($price) <= 0) {
        $errors['price'] = "Le prix doit être un nombre entier positif";
    }

    if (!preg_match($cityName_pattern, $city)) {
        $errors['city'] = "La ville doit être comprise entre 1 et 150 caractères valides";
    }

    if (strlen($description) < 50 || strlen($description) > 1000) {
        $errors['description'] = "La description doit être comprise entre 50 et 1000 caractères";
    }

    if ($property_type !== "House" && $property_type !== "Apartment") {
        $errors['property_type'] = "Type de propriété invalide";
    }

    if ($transaction_type !== "Rent" && $transaction_type !== "Sale") {
        $errors['transaction_type'] = "Type de transaction invalide";
    }

    // Handle file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $fileTmp = $_FILES['image_file']['tmp_name'];
        $fileSize = $_FILES['image_file']['size'];
        $fileType = $_FILES['image_file']['type'];
        $fileName = $_FILES['image_file']['name'];

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024;

        if (!in_array($fileType, $allowedTypes)) {
            $errors['image_file'] = "Type de fichier non autorisé";
        } elseif ($fileSize > $maxFileSize) {
            $errors['image_file'] = "Fichier trop volumineux (max 5Mo)";
        } else {
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('file_', true) . '.' . $fileExtension;

            $uploadFolder = $property_type === "Apartment" ? "apartments" : "houses";
            $uploadDir = __DIR__ . '/../../../../public/assets/images/listings/' . $uploadFolder . '/';
            $uploadPath = $uploadDir . $newFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $imagePath = '/assets/images/listings/' . $uploadFolder . '/' . $newFileName;
            } else {
                $errors['image_file'] = "Erreur lors du téléchargement du fichier";
            }
        }
    }

    // Update in DB
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE listing SET 
                title = :title,
                description = :description,
                price = :price,
                city = :city,
                image_URL = :image_URL,
                property_type_id = :property_type_id,
                transaction_type_id = :transaction_type_id,
                updated_at = NOW()
            WHERE id = :id'
        );

        $property_type_id = $property_type === "House" ? 1 : 2;
        $transaction_type_id = $transaction_type === "Rent" ? 1 : 2;

        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":price", $price, PDO::PARAM_INT);
        $stmt->bindValue(":city", $city);
        $stmt->bindValue(":image_URL", $imagePath);
        $stmt->bindValue(":property_type_id", $property_type_id, PDO::PARAM_INT);
        $stmt->bindValue(":transaction_type_id", $transaction_type_id, PDO::PARAM_INT);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../../index.php");
        exit();
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" minlength="5" maxlength="255" required value="<?= htmlspecialchars($title) ?>">
    <span class="error"><?= $errors['title'] ?? "" ?></span>

    <label>Upload image (leave empty to keep current):</label>
    <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp">
    <?php if ($imagePath): ?>
        <img src="<?= $imagePath ?>" alt="Current image" style="max-width:150px; display:block; margin:5px 0;">
    <?php endif; ?>
    <span class="error"><?= $errors['image_file'] ?? "" ?></span>

    <label>Price:</label>
    <input type="number" name="price" min="1" required value="<?= htmlspecialchars($price) ?>">
    <span class="error"><?= $errors['price'] ?? "" ?></span>

    <label>City:</label>
    <input type="text" name="city" required value="<?= htmlspecialchars($city) ?>">
    <span class="error"><?= $errors['city'] ?? "" ?></span>

    <label>Description:</label>
    <textarea name="description" minlength="50" maxlength="1000" required><?= htmlspecialchars($description) ?></textarea>
    <span class="error"><?= $errors['description'] ?? "" ?></span>

    <label>Property type:</label>
    <select name="property_type" required>
        <option value="" hidden>--select type--</option>
        <option value="House" <?= $property_type === 'House' ? 'selected' : '' ?>>House</option>
        <option value="Apartment" <?= $property_type === 'Apartment' ? 'selected' : '' ?>>Apartment</option>
    </select>
    <span class="error"><?= $errors['property_type'] ?? "" ?></span>

    <label>Transaction type:</label>
    <select name="transaction_type" required>
        <option value="" hidden>--select type--</option>
        <option value="Rent" <?= $transaction_type === 'Rent' ? 'selected' : '' ?>>Rent</option>
        <option value="Sale" <?= $transaction_type === 'Sale' ? 'selected' : '' ?>>Sale</option>
    </select>
    <span class="error"><?= $errors['transaction_type'] ?? "" ?></span>

    <button type="submit">Mettre à jour</button>
</form>