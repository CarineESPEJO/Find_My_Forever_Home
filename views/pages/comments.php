<?php
session_start();

$commentsFile = __DIR__ . "/comments.txt";

if (isset($_SESSION["isLoggedIn"]) && $_SERVER["REQUEST_METHOD"] === "POST" ) {
    $username = $_SESSION["user"];
    $comment = trim($_POST["comment"]);

    if (!empty($comment)) {
        $entry = time() . "|" . $username . "|" . str_replace(["\n", "|"], " ", $comment) . PHP_EOL;
        file_put_contents($commentsFile, $entry, FILE_APPEND);
    }
}

$comments = [];
if (file_exists($commentsFile)) {
    $lines = file($commentsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        [$timestamp, $user, $text] = explode("|", $line);
        $comments[] = [
            "date" => date("Y-m-d H:i", (int)$timestamp),
            "user" => $user,
            "text" => $text
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find my dream home - Commentaires</title>
    <link rel="stylesheet" href="/assets/css_files/global_style.css">
    <link rel="stylesheet" href="/assets/css_files/connexion_style.css">
</head>
<body>

<?php require_once("../common_components/header.php"); ?>
    
<?php if (!empty($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true ): ?>
    <p>
        Bienvenue, <?php echo $_SESSION["user"] ?> |
        <a href="logout.php">Déconnexion</a>
    </p>

    <h2>Ajouter un commentaire</h2>
    <form action="comments.php" method="post">
        <textarea name="comment" rows="4" cols="50" placeholder="Votre commentaire..." required></textarea><br>
        <button type="submit">Envoyer</button>
    </form>

<?php else: ?>
    <p>🔒 Vous devez être <a href="login.php">connecté</a> pour laisser un commentaire.</p>
<?php endif; ?>

<hr>

<h2>Tous les commentaires</h2>

<?php if (empty($comments)): ?>
    <p>Aucun commentaire pour l’instant.</p>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div style="border: 1px solid #ccc; margin: 10px 0; padding: 10px;">
            <strong><?= htmlspecialchars($comment["user"]) ?></strong>
            <em>(<?php echo $comment["date"] ?>)</em><br>
            <p><?php echo $comment["text"] ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php require_once("../common_components/header.php"); ?>
</body>
</html>
