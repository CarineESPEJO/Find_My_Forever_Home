<?php
session_start();

use App\Core\Database;

require_once __DIR__ . '/../app/Core/Database.php';

// Establish DB connection
$pdo = Database::getConnection();

// Determine which form to include
$form = $_GET['form'] ?? null;

// Access control
$allowedForms = ['login', 'register', 'AddListing', 'UpdateListing'];
if (!in_array($form, $allowedForms)) {
    $form = 'login'; // default form
}

// Redirect logged-in users away from login form
if ($form === 'login' && !empty($_SESSION['userId'])) {
    header('Location: ../../../index.php');
    exit;
}

// Additional access control
if (($form === 'AddListing' || $form === 'UpdateListing') && empty($_SESSION['userId'])) {
    header('Location: formsLayout.php?form=login');
    exit;
}

// Map form to h2 titles
$formTitles = [
    'login' => 'Connexion',
    'register' => 'Inscription',
    'AddListing' => 'Créer une annonce',
    'UpdateListing' => 'Modifier une annonce'
];

$h2Title = $formTitles[$form] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($h2Title) ?> Form</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/header_footer.css">
    <link rel="stylesheet" href="/assets/css/forms.css">

    <!-- JS -->
    <?php if ($form === 'AddListing' || $form === 'UpdateListing'): ?>
        <script src="/assets/js/listingForms.js" defer></script>
    <?php endif; ?>
</head>

<body>

    <?php require_once __DIR__ . '/../app/Views/Components/Header.php'; ?>

    <div class="body-wrapper">
        <aside>
            <img src="/assets/images/login_img.jpeg" alt="Login visual" />
        </aside>

        <main>
            <h2><?= htmlspecialchars($h2Title) ?></h2>
            <?php
            switch ($form) {
                case 'login':
                    include __DIR__ . '/../app/Views/Forms/Log/LoginForm.php';
                    break;
                case 'register':
                    include __DIR__ . '/../app/Views/Forms/Log/RegisterForm.php';
                    break;
                case 'AddListing':
                    include __DIR__ . '/../app/Views/Forms/Listing/AddListing.php';
                    break;
                case 'UpdateListing':
                    include __DIR__ . '/../app/Views/Forms/Listing/UpdateListing.php';
                    break;
            }
            ?>
        </main>
    </div>

    <?php require_once __DIR__ . '/../app/Views/Components/Footer.php'; ?>

</body>

</html>