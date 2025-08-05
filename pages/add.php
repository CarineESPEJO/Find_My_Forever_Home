<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);


    if ($email === "admin@email.com" && $password === "azerty") {
        session_regenerate_id(true);
        $_SESSION["user"] = "admin";
        $_SESSION["isLoggedIn"] = true;
        header("Location: comments.php");
        exit();
    } else {
        echo "<p style='color:red;'>Identifiants incorrects.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/global_style.css">
    <link rel="stylesheet" href="connexion_style.css">
</head>

<body>
    <?php require_once("../views/common_views/header.php"); ?>

    <div class="body-wrapper">
        <aside>
            <img src="/assets/images/login_img.jpeg" alt="Login visual" />
        </aside>

        <main>
            <h2>New Add</h2>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>




                <form action="" method="post">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>

                    <label for="imageUpload">Upload an image:</label>
                    <input type="file" id="imageUpload" name="imageUpload" accept="image/*" required>


                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" required>

                    <label for="location">Ville:</label>
                    <input type="text" id="location" name="location" required>

                    <label for="description">Description:</label>
                    <textarea name="description" rows="4" cols="50" placeholder="Votre description..." maxlength="150"
                        required></textarea><br>

                    <label for="property_type">Type de propriété:</label>
                    <select id="property_type" name="property_type" required>
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
    <?php require_once("../views/common_views/footer.php"); ?>
</body>

</html>