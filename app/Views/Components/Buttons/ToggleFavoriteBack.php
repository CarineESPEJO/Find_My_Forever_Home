<?php

require_once __DIR__ . '/../../../Core/Database.php';

use App\Core\Database;

header('Content-Type: application/json');

$pdo = Database::getConnection();

$userId = $_SESSION['userId'] ?? null;
$listingId = $_POST['annonce_id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

if (!$listingId || !ctype_digit($listingId)) {
    echo json_encode(['error' => 'ID annonce invalide']);
    exit;
}

try {
    // Check if favorite exists
    $stmt = $pdo->prepare("SELECT 1 FROM favorite WHERE user_id = :user_id AND listing_id = :listing_id LIMIT 1");
    $stmt->execute([':user_id' => $userId, ':listing_id' => $listingId]);
    $isFavorited = (bool)$stmt->fetchColumn();

    if ($isFavorited) {
        $stmt = $pdo->prepare("DELETE FROM favorite WHERE user_id = :user_id AND listing_id = :listing_id");
        $stmt->execute([':user_id' => $userId, ':listing_id' => $listingId]);
        echo json_encode(['status' => 'removed']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO favorite (user_id, listing_id) VALUES (:user_id, :listing_id)");
        $stmt->execute([':user_id' => $userId, ':listing_id' => $listingId]);
        echo json_encode(['status' => 'added']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur serveur: ' . $e->getMessage()]);
}
