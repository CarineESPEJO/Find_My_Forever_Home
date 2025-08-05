<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php require_once("../views/common_views/header.php"); ?>

    <main>
        <div>
            <h2>Connexion</h2>
            <form>
                <form action="index_logged.php" method="post">
                    <label for="email">email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">mot de passe:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit">Envoyer</button>
                </form>

                <a href="register.php">Inscription</a>
        </div>
    </main>

    <?php require_once("../views/common_views/footer.php"); ?>
</body>

</html>