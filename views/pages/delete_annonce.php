<?php

if (!isset($pdo)) {
    require_once("../common_components/pdo_connexion.php");
}

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit($id)) {
    header("Location: /index.php");
    exit();
}

$stmt = $pdo->prepare(
    'DELETE FROM listing 
     WHERE id = :id'
);

$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: ../../index.php");
exit();
