<?php
session_start();
$errors = [];
$account_created = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $email_pattern = '/^[\w\.-]+@[\w\.-]+\.\w+$/';
    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/';

    if (empty($email)) {
        $errors[] = "L'email est obligatoire";
    } elseif (!preg_match($email_pattern, $email)) {
        $errors[] = "Email non reconnu";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire";
    } elseif (!preg_match($password_pattern, $password)) {
        $errors[] = "Le mot de passe doit contenir au moins 9 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial";
    }

    if ($confirm_password != $password) {
     $errors[] = "Les mots de passes ne sont pas identiques.";
    }


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
    <title>Connexion</title>
    <link rel="stylesheet" href="/style.css">
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
            <?php else: ?>

                <?php if ($account_created): ?>
                    <h3 style="color: green;">Votre compte a été créé</h3>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <ul style="color: red;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="" method="post">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" minlength="9" required>

                    <label for="confirm_password">Confirmer le mot de passe:</label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="9" required>

                    <button class="login" type="submit">S'incrire</button>
                </form>
            <?php endif; ?>
            <a href="login.php">Déjà inscrit ? Connectez-vous ?</a>
        
    </main>
</div>
    <?php require_once("../views/common_views/footer.php"); ?>
</body>

</html>