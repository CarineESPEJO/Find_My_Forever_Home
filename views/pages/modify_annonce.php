<?php
session_start();
require_once("../common_components/pdo_connexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $image_URL = trim($_POST['image_URL'] ?? '');
    $price = $_POST['price'] ?? '';
    $city = strtolower(trim($_POST['city'] ?? ''));
    $description = trim($_POST['description'] ?? '');
    $property_type = $_POST['property_type'] ?? '';
    $transaction_type = $_POST['transaction_type'] ?? '';

    $user_id = $_SESSION['userId'] ?? null;
    $errors = [];

    $imageURL_pattern = $imageURL_pattern = '/^https?:\/\/[^\s]+?\.(jpg|jpeg|png|webp)$/i';
    $cityName_pattern = '/^[\p{L}\p{M}\s\'\.\-ʻ’]{1,150}$/u';

    if (!isset($_POST['title'])) {
        $errors['title'] = "Le titre est obligatoire";
    } elseif (!(strlen(trim($_POST['title'])) >= 5 && strlen($_POST['title']) <= 255)) {
        $errors['title'] = "Le titre doit être compris entre 5 et 255 caractères";
    }
    ;

    if (!isset($_POST['image_URL'])) {
        $errors['image_URL'] = "Ajouté l'url d'une image svp";
    } elseif (strlen($_POST['image_URL']) > 150) {
        $errors['image_URL'] = "L'Url de l'image doit faire 255 caractères maximum.";
    } elseif (!preg_match($imageURL_pattern, $_POST['image_URL'])) {
        $errors['image_URL'] = "L'URL de l'image n'est pas valide. Formats acceptés : jpg, jpeg, png, webp";
    }

    ;

    if (!isset($_POST['price'])) {
        $errors['price'] = "Le prix est obligatoire";
    } elseif (!ctype_digit($_POST['price']) || intval($_POST['price']) <= 0) {
        $errors['price'] = "Le prix doit être un nombre entier au-dessus de 0";
    }
    ;

    if (!isset($_POST['city'])) {
        $errors['city'] = "La ville est obligatoire";
    } elseif (!preg_match($cityName_pattern, $_POST['city'])) {
        $errors['city'] = "La ville doit être comprise entre 1 et 150 caractères";
    }
    ;

    if (!isset($_POST['description'])) {
        $errors['description'] = "La description est obligatoire";
    } elseif (!(strlen($_POST['description']) >= 50 && strlen($_POST['description']) <= 1000)) {
        $errors['description'] = "La description doit être comprise entre 50 et 1000 caractères";
    }
    ;

    if (!isset($_POST['property_type'])) {
        $errors['property_type'] = "Le type de propriété est obligatoire";
    } elseif ($_POST['property_type'] != "House" && $_POST['property_type'] != "Apartment") {
        $errors['property_type'] = "Le type de propriété doit être soit House, soit Apartment";
    }
    ;

    if (!isset($_POST['transaction_type'])) {
        $errors['transaction_type'] = "Le type de transaction est obligatoire";
    } elseif ($_POST['transaction_type'] != "Rent" && $_POST['transaction_type'] != "Sale") {
        $errors['transaction_type'] = "Le type de transaction doit être soit Rent, soit Sale";
    }

    ;

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO listing (title, description, price, city, image_URL, property_type_id, transaction_type_id, user_id, updated_at) VALUES
                                        (:title, :description, :price, :city, :image_URL, :property_type_id, :transaction_type_id, :user_id, NOW());');
        $property_type_id = $property_type === "House" ? 1 : 2;
        $transaction_type_id = $transaction_type === "Rent" ? 1 : 2;

        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->bindValue(":price", $price, PDO::PARAM_INT);
        $stmt->bindValue(":city", $city, PDO::PARAM_STR);
        $stmt->bindValue(":image_URL", $image_URL, PDO::PARAM_STR);
        $stmt->bindValue(":property_type_id", $property_type_id, PDO::PARAM_INT);
        $stmt->bindValue(":transaction_type_id", $transaction_type_id, PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();
        header("Location: ../../index.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css">
    <link rel="stylesheet" href="/assets/css_files/connexion_style.css">
    <script src="/assets/js_files/add_annonce.js" defer></script>
</head>

<body>
    <?php require_once("../common_components/header.php"); ?>

    <div class="body-wrapper">
        <aside>
            <img src="/assets/images/login_img.jpeg" alt="Login visual" />
        </aside>

        <main>
            <h2>New Add</h2>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>




                <form action="" method="post">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" minlength="5" maxlength="50" required
                        value="<?php echo htmlspecialchars($title ?? '') ?>">
                    <span id="titleError" class="error"><?php echo isset($errors["title"]) ? $errors["title"] : "" ?>
                    </span>

                    <label for="image_URL">URL image:</label>
                    <input type="text" id="image_URL" name="image_URL" required
                        value="<?php echo htmlspecialchars($image_URL ?? '') ?>">
                    <span id="imgURLError"
                        class="error"><?php echo isset($errors["image_URL"]) ? $errors["image_URL"] : "" ?> </span>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" min="1" required
                        value="<?php echo htmlspecialchars($price ?? '') ?>">
                    <span id="priceError" class="error"><?php if (isset($errors["price"])) {
                        echo $errors["price"];
                    } ?></span>

                    <label for="city">Ville:</label>
                    <input type="text" id="city" name="city" required value="<?php echo htmlspecialchars($city ?? '') ?>">
                    <span id="cityError" class="error"><?php if (isset($errors["city"])) {
                        echo $errors["city"];
                    } ?></span>


                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50" placeholder="Votre description..."
                        minlength="50" maxlength="255"
                        required><?php echo htmlspecialchars($description ?? '') ?></textarea><br>
                    <span id="descError" class="error"><?php if (isset($errors["description"])) {
                        echo $errors["description"];
                    } ?></span>


                    <label for="property_type">Type de propriété:</label>
                    <select id="property_type" name="property_type" required>
                        <option value="" disabled selected hidden <?= empty($property_type) ? 'selected' : '' ?>>--select
                            type--</option>
                        <option value="House" <?= isset($property_type) && $property_type === 'House' ? 'selected' : '' ?>>
                            House</option>
                        <option value="Apartment" <?= isset($property_type) && $property_type === 'Apartment' ? 'selected' : '' ?>>Apartment</option>

                    </select>
                    <span id="proptypeError" class="error"><?php if (isset($errors["property_type"])) {
                        echo $errors["property_type"];
                    } ?></span>


                    <label for="transaction_type">Type de transaction:</label>
                    <select id="transaction_type" name="transaction_type" required>
                        <option value="" disabled selected hidden <?= empty($transaction_type) ? 'selected' : '' ?>>--select
                            type--</option>
                        <option value="Rent" <?= isset($transaction_type) && $transaction_type === 'Rent' ? 'selected' : '' ?>>
                            Rent</option>
                        <option value="Sale" <?= isset($transaction_type) && $transaction_type === 'Sale' ? 'selected' : '' ?>>
                            Sale</option>
                    </select>
                    <span id="transtypeError" class="error"><?php if (isset($errors["transaction_type"])) {
                        echo $errors["transaction_type"];
                    } ?></span>


                    <button id="submitButton" class="login" type="submit">Enregistrer</button>
                </form>
            <?php elseif ($_SESSION["userRole"] != "admin" && $_SESSION["userRole"] != "agent"): ?>
                <p>🔒 Vous n'avez pas les droits pour ajouter une annonce.</p>
                <? header("Location: ../../../index.php");
                exit();
            else: ?>
                <p>🔒 Vous devez être <a href="connexion/login.php">connecté</a> pour ajouter une annonce.</p>

            <?php endif; ?>

        </main>
    </div>
    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>