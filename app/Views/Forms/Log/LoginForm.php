<?php
// LoginForm.php
// No session_start() here
// No require_once for Database here

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use $pdo from formsLayout.php
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION["userId"] = $user["id"];
        $_SESSION["userRole"] = $user["role"];
        $_SESSION["isLoggedIn"] = true;
        header("Location: ../../../index.php");
        exit();
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Login</button>
</form>

<a href="formsLayout.php?form=register">Pas encore inscrit ? Inscrivez-vous</a>