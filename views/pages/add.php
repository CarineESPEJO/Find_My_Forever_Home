<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['title'])) {
        $errors['title'] = "Le titre est obligatoire";
    } elseif (strlen(trim($_POST['title'])) >= 5 && strlen($_POST['title']) <= 5) {
        $errors['title'] = "Le titre doit être compris entre 5 et 50 caractères";
    }
    ;

    if (!isset($_POST['imageUpload'])) {
        $errors['imageUpload'] = "Ajouté une image svp";
    } elseif (!in_array($_POST['imageUpload'], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
        $errors['imageUpload'] = "L'image doit être un fichier JPEG, PNG ou WEBP";
    }
    ;

    if (!isset($_POST['price'])) {
        $errors['price'] = "Le prix est obligatoire";
    } elseif (is_int(['price'])) {
        $errors['price'] = "Le prix doit est un nombre entier";
    }
    ;

    if (!isset($_POST['location'])) {
        $errors['location'] = "La ville est obligatoire";
    } elseif (strlen($_POST['location']) > 0 && strlen($_POST['location']) < 85) {
        $errors['location'] = "La ville doit être comprise entre 1 et 85 caractères";
    }
    ;

    if (!isset($_POST['description'])) {
        $errors['description'] = "La description est obligatoire";
    } elseif (strlen($_POST['description']) >= 50 && strlen($_POST['location']) <= 255) {
        $errors['description'] = "La description doit être comprise entre 50 et 255 caractères";
    }
    ;

    if (!isset($_POST['property_type'])) {
        $errors['property_type'] = "Le type de propriété est obligatoire";
    } elseif (strlen($_POST['property_type']) >= 50 && strlen($_POST['location']) <= 255) {
        $errors['property_type'] = "La description doit être comprise entre 50 et 255 caractères";
    }
    ;



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

                    <label for="imageUpload">Upload an image:</label>
                    <input type="file" id="imageUpload" name="imageUpload" accept=".png, .jpg, .jpeg, .webp" required>


                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>

                    <label for="location">Ville:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="description">Description:</label>
                    <textarea name="description" rows="4" cols="50" placeholder="Votre description..." minlegth="50"
                        maxlength="255" required></textarea><br>

                    <label for="property_type">Type de propriété:</label>
                    <select id="property_type" name="property_type" required>
                        <option value="" disabled selected hidden>--select type--</option>
                        <option value="House">House</option>
                        <option value="Appartement">Appartement</option>
                    </select>

                    <label for="transaction_type">Type de transaction:</label>
                    <select id="transaction_type" name="transaction_type" required>
                        <option value="" disabled selected hidden>--select type--</option>
                        <option value="Rent">Rent</option>
                        <option value="Sale">Sale</option>
                    </select>

                    <button class="login" type="submit">Enregistrer</button>
                </form>
                <a href="../index.php">Retour à l'accueil</a>
            <?php else: ?>
                <p>🔒 Vous devez être <a href="login.php">connecté</a> pour ajouter une annonce.</p>
            <?php endif; ?>

        </main>
    </div>
    <?php require_once("../common_components/footer.php"); ?>
</body>

</html>