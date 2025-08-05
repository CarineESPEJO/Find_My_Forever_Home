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
            <h2>Connexion</h2>
            <?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true): ?>

                <p> Vous êtes déjà connectés</p>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>

           
                <form action="" method="post">
                    <label for="email">email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">mot de passe:</label>
                    <input type="password" id="password" name="password" required>

                    <button class="login" type="submit">Login</button>
                </form>
                <a href="register.php">Pas encore de compte? Inscrivez-vous</a>
            <?php endif; ?>
            
     </main>
  </div>
  <?php require_once("../views/common_views/footer.php"); ?>
</body>

</html>