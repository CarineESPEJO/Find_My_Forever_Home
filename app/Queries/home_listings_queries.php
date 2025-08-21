<?php
if (!isset($pdo)) {
    require_once("views/common_components/pdo_connexion.php");
}

$stmt = "";

if ($stmt_is_house) {
    $stmt = $pdo->prepare(
        'SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type, tratyp.name AS transaction_type, city, description, lis.created_at AS created_at,lis.updated_at AS updated_at 
         FROM listing AS lis
         JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
         JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
         WHERE protyp.name = "House"
         ORDER BY lis.id DESC
         LIMIT 3;'
    );
} else {
    $stmt = $pdo->prepare(
        'SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type, tratyp.name AS transaction_type, city, description, lis.created_at AS created_at,lis.updated_at AS updated_at
         FROM listing AS lis
         JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
         JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
         WHERE protyp.name = "Apartment"
         ORDER BY lis.id DESC
         LIMIT 3;'
    );
}

$stmt->execute(); 
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
