<?php
// RegisterForm.php
// No session_start()
// No require_once for Database here

$errors = [];
$account_created = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/';

    // Email validation
    if (empty($email)) {
        $errors["email"] = "L'email est obligatoire";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Email non valide";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors["email"] = "Cet email est déjà utilisé";
        }
    }

    // Password validation
    if (empty($password)) {
        $errors["password"] = "Le mot de passe est obligatoire";
    } elseif (!preg_match($password_pattern, $password)) {
        $errors["password"] = "Le mot de passe doit contenir au moins 9 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial";
    }

    // Confirm password
    if ($confirm_password !== $password) {
        $errors["confirm_password"] = "Les mots de passe ne sont pas identiques.";
    }

    // Insert user if no errors
    // If no errors, insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $now = date('Y-m-d H:i:s'); // current timestamp

        $stmt = $pdo->prepare(
            "INSERT INTO user (email, password, role, created_at, updated_at) 
         VALUES (?, ?, 'user', ?, ?)"
        );

        if ($stmt->execute([$email, $hashed_password, $now, $now])) {
            $account_created = true;
        } else {
            $errors["general"] = "Une erreur est survenue, veuillez réessayer";
        }
    }
}
?>

<?php if (!empty($_SESSION["isLoggedIn"])): ?>
    <p>Vous êtes déjà connecté.</p>
<?php else: ?>

    <?php if ($account_created): ?>
        <h3 style="color: green;">Votre compte a été créé avec succès</h3>
    <?php endif; ?>

    <?php if (isset($errors["general"])): ?>
        <p style="color: red;"><?= htmlspecialchars($errors["general"]) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <span style="color:red;">
            <?= $errors["email"] ?? '' ?>
        </span>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" minlength="9" required>
        <span style="color:red;">
            <?= $errors["password"] ?? '' ?>
        </span>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" minlength="9" required>
        <span style="color:red;">
            <?= $errors["confirm_password"] ?? '' ?>
        </span>

        <button type="submit">S'inscrire</button>
    </form>

    <a href="formsLayout.php?form=login">Déjà inscrit ? Connectez-vous</a>

<?php endif; ?>