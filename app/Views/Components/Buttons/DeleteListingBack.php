<?php
require_once __DIR__ . '/../../../../app/Core/Database.php';

use App\Core\Database;

$pdo = Database::getConnection();

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit($id)) {
    header("Location: /index.php");
    exit();
}

$stmt = $pdo->prepare('DELETE FROM listing WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: /index.php");
exit();
