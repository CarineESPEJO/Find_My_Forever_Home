<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $imageURL_pattern = $imageURL_pattern = '/^https?:\/\/[^\s]+?\.(jpg|jpeg|png|webp)$/i';
    $locName_pattern = '/^[\p{L}\p{M}\s\'\.\-ʻ’]{1,100}$/u';

    if (!isset($_POST['title'])) {
        $errors['title'] = "Le titre est obligatoire";
    } elseif (!(strlen(trim($_POST['title'])) >= 5 && strlen($_POST['title']) <= 50)) {
        $errors['title'] = "Le titre doit être compris entre 5 et 50 caractères";
    }
    ;

    if (!isset($_POST['imageUpload'])) {
        $errors['imageUpload'] = "Ajouté l'url d'une image svp";
    } elseif (!preg_match($imageURL_pattern, $_POST['imageUpload'])) {
        $errors['price'] = "L'URL de l'image n'est pas valide. Formats acceptés : jpg, jpeg, png, webp";;
    }

    ;

    if (!isset($_POST['price'])) {
        $errors['price'] = "Le prix est obligatoire";
    } elseif (!ctype_digit($_POST['price']) || intval($_POST['price']) <= 0) {
        $errors['price'] = "Le prix doit être un nombre entier au-dessus de 0";
    }
    ;

    if (!isset($_POST['location'])) {
        $errors['location'] = "La ville est obligatoire";
    } elseif (!preg_match($locName_pattern, $_POST['location'])) {
        $errors['location'] = "La ville doit être comprise entre 1 et 85 caractères";
    }
    ;

    if (!isset($_POST['description'])) {
        $errors['description'] = "La description est obligatoire";
    } elseif (!(strlen($_POST['description']) >= 50 && strlen($_POST['description']) <= 255)) {
        $errors['description'] = "La description doit être comprise entre 50 et 255 caractères";
    }
    ;

    if (!isset($_POST['property_type'])) {
        $errors['property_type'] = "Le type de propriété est obligatoire";
    } elseif ($_POST['property_type'] != "House" && $_POST['property_type'] != "Appartement") {
        $errors['property_type'] = "Le type de propriété doit être soit House, soit Appartement";
    }
    ;

    if (!isset($_POST['transaction_type'])) {
        $errors['transaction_type'] = "Le type de transaction est obligatoire";
    } elseif ($_POST['transaction_type'] != "Rent" && $_POST['transaction_type'] != "Sale") {
    $errors['transaction_type'] = "Le type de transaction doit être soit Rent, soit Sale";
}

    ;

    if (empty($errors)) {
        $account_created = true;
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
                    <input type="text" id="title" name="title" minlength="5" maxlength="50" required>
                    <span id="titleError" class="error"><?php echo isset($errors["title"]) ? $errors["title"] : "" ?>
                    </span>

                    <label for="imageUpload">URL image:</label>
                    <input type="text" id="imageUpload" name="imageUpload" required>
                    <span id="imgUploadError"
                        class="error"><?php echo isset($errors["imageUpload"]) ? $errors["imageUpload"] : "" ?> </span>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" min="1" required>
                    <span id="priceError" class="error"><?php if (isset($errors["price"])) {
                        echo $errors["price"];
                    } ?></span>

                    <label for="location">Ville:</label>
                    <input type="text" id="location" name="location" required>
                    <span id="locationError" class="error"><?php if (isset($errors["location"])) {
                        echo $errors["location"];
                    } ?></span>


                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50" placeholder="Votre description..."
                        minlength="50" maxlength="255" required></textarea><br>
                    <span id="descError" class="error"><?php if (isset($errors["description"])) {
                        echo $errors["description"];
                    } ?></span>


                    <label for="property_type">Type de propriété:</label>
                    <select id="property_type" name="property_type" required>
                        <option value="" disabled selected hidden>--select type--</option>
                        <option value="House">House</option>
                        <option value="Appartement">Appartement</option>
                    </select>
                    <span id="proptypeError" class="error"><?php if (isset($errors["property_type"])) {
                        echo $errors["property_type"];
                    } ?></span>


                    <label for="transaction_type">Type de transaction:</label>
                    <select id="transaction_type" name="transaction_type" required>
                        <option value="" disabled selected hidden>--select type--</option>
                        <option value="Rent">Rent</option>
                        <option value="Sale">Sale</option>
                    </select>
                    <span id="transtypeError" class="error"><?php if (isset($errors["transaction_type"])) {
                        echo $errors["transaction_type"];
                    } ?></span>


                    <button id="submitButton" class="login" type="submit">Enregistrer</button>
                </form>
            <?php else: ?>
                <p>🔒 Vous devez être <a href="connexion/login.php">connecté</a> pour ajouter une annonce.</p>
            <?php endif; ?>

        </main>
    </div>
    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>