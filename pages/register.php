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

               <?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $errors = [];
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

    if (empty($errors)) {
        
    }
}
?>


                <a href="login.php">Connexion</a>
        </div>
    </main>

    <?php require_once("../views/common_views/footer.php"); ?>
</body>

</html>