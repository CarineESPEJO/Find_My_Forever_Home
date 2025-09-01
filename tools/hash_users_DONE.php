<?php
// Load the Database file to connect to the database FMDH
require_once __DIR__ . '/../app/Core/Database.php';
// Call the class Database and save in pdo
$pdo = \App\Core\Database::getConnection();

$stmt = $pdo->query("SELECT id, password FROM user");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $id = $user['id'];
    $plainPassword = $user['password'];

    // Nouvelle vérification fiable : si ça ne commence pas par $2y$, on considère que ce n’est pas hashé
    if (!preg_match('/^\$2y\$/', $plainPassword)) {
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $update = $pdo->prepare("UPDATE user SET password = :pass WHERE id = :id");
        $update->bindValue(':pass', $hashedPassword, PDO::PARAM_STR);
        $update->bindValue(':id', $id, PDO::PARAM_INT);
        $update->execute();

        echo "Password for user ID $id has been hashed.<br>";
    } else {
        echo "Password for user ID $id already hashed.<br>";
    }
}
?>
