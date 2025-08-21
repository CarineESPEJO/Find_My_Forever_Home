<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class ListingRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Fetch all listings
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type,
                   tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            ORDER BY lis.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch listings by type
    public function getByType(string $type): array
    {
        $stmt = $this->db->prepare("
            SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type,
                   tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE protyp.name = :type
            ORDER BY lis.id DESC
        ");
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch listings by type with pagination
    public function getByTypeWithOffset(string $type, int $offset = 0, int $limit = 12): array
    {
        $stmt = $this->db->prepare("
            SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type,
                   tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE protyp.name = :type
            ORDER BY lis.id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total listings by type
    public function countByType(string $type): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            WHERE protyp.name = :type
        ");
        $stmt->execute(['type' => $type]);
        return (int)$stmt->fetchColumn();
    }

    // Fetch listing by ID
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type,
                   tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE lis.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Fetch favorites of a user with pagination
    public function getFavoritesByUser(int $userId, int $offset = 0, int $limit = 12): array
    {
        $stmt = $this->db->prepare("
        SELECT lis.*, protyp.name AS property_type, tratyp.name AS transaction_type
        FROM listing AS lis
        JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
        JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
        JOIN favorite AS fav ON lis.id = fav.listing_id
        WHERE fav.user_id = :user_id
        ORDER BY fav.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total favorites for a user
    public function countFavoritesByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
