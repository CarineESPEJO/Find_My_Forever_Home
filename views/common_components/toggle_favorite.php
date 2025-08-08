<?php
session_start();
require_once __DIR__ . "/../common_components/pdo_connexion.php";

header('Content-Type: application/json');

$userId = $_SESSION['userId'] ?? null;
$annonceId = $_POST['annonce_id'] ?? null;

if (!$userId) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

if (!$annonceId || !ctype_digit($annonceId)) {
    echo json_encode(['error' => 'ID annonce invalide']);
    exit;
}


$stmt = $pdo->prepare("SELECT 1 FROM favorite WHERE user_id = :user_id AND listing_id = :listing_id LIMIT 1");
$stmt->execute([':user_id' => $userId, ':listing_id' => $annonceId]);
$isFavorited = (bool) $stmt->fetchColumn();

if ($isFavorited) {

    $stmt = $pdo->prepare("DELETE FROM favorite WHERE user_id = :user_id AND listing_id = :listing_id");
    $stmt->execute([':user_id' => $userId, ':listing_id' => $annonceId]);
    echo json_encode(['status' => 'removed']);
} else {
   
    $stmt = $pdo->prepare("INSERT INTO favorite (user_id, listing_id) VALUES (:user_id, :listing_id)");
    $stmt->execute([':user_id' => $userId, ':listing_id' => $annonceId]);
    echo json_encode(['status' => 'added']);
}
