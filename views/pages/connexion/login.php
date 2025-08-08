<?php
session_start();
require_once("../../common_components/pdo_connexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $error = "";

    $stmt = $pdo->prepare('SELECT * FROM user WHERE email = :email');
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION["userId"] = $user["id"];
        $_SESSION["userRole"] = $user["role"];
        $_SESSION["isLoggedIn"] = true;
        header("Location: ../../index.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
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
</head>

<body>
    <?php require_once("../../common_components/header.php"); ?>

    <div class="body-wrapper">
        <aside>
            <img src="/assets/images/login_img.jpeg" alt="Login visual" />
        </aside>

        <main>
            <h2>Connexion</h2>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>

                <p> Vous êtes déjà connectés</p>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>

                <span><?php if (!empty($error)) {
                    echo $error;
                } ?></span>

                <form action="" method="post">
                    <label for="email">email:</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">

                    <label for="password">mot de passe:</label>
                    <input type="password" id="password" name="password" required>

                    <button class="login" type="submit">Login</button>
                </form>
                <a href="register.php">Pas encore de compte? Inscrivez-vous</a>
            <?php endif; ?>

        </main>
    </div>
    <?php require_once("../../common_components/footer.php"); ?>
</body>

</html>