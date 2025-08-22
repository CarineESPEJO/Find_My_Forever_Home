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

    // --- Type-specific listings (House / Apartment) ---
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

    public function countByType(string $type): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            WHERE protyp.name = :type
        ");
        $stmt->execute([':type' => $type]);
        return (int)$stmt->fetchColumn();
    }

    // --- All listings with optional filters (Search page) ---
    public function search(array $filters = [], int $offset = 0, int $limit = 12): array
    {
        $sql = "
            SELECT lis.id AS id, image_URL, title, price, protyp.name AS property_type,
                   tratyp.name AS transaction_type, city, description, lis.created_at, lis.updated_at
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['city'])) {
            $sql .= " AND city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = (int)$filters['max_price'];
        }

        if (!empty($filters['property_type'])) {
            $sql .= " AND protyp.id = :property_type";
            $params[':property_type'] = (int)$filters['property_type'];
        }

        if (!empty($filters['transaction_type'])) {
            $sql .= " AND tratyp.id = :transaction_type";
            $params[':transaction_type'] = (int)$filters['transaction_type'];
        }

        $sql .= " ORDER BY lis.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearch(array $filters = []): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM listing AS lis
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['city'])) {
            $sql .= " AND city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = (int)$filters['max_price'];
        }

        if (!empty($filters['property_type'])) {
            $sql .= " AND protyp.id = :property_type";
            $params[':property_type'] = (int)$filters['property_type'];
        }

        if (!empty($filters['transaction_type'])) {
            $sql .= " AND tratyp.id = :transaction_type";
            $params[':transaction_type'] = (int)$filters['transaction_type'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // --- User's favorites with optional filters ---
    public function searchFavorites(int $userId, array $filters = [], int $offset = 0, int $limit = 12): array
    {
        $sql = "
            SELECT lis.*, protyp.name AS property_type, tratyp.name AS transaction_type
            FROM favorite AS f
            JOIN listing AS lis ON f.listing_id = lis.id
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE f.user_id = :user_id
        ";

        $params = [':user_id' => $userId];

        if (!empty($filters['city'])) {
            $sql .= " AND lis.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND lis.price <= :max_price";
            $params[':max_price'] = (int)$filters['max_price'];
        }

        if (!empty($filters['property_type'])) {
            $sql .= " AND protyp.id = :property_type";
            $params[':property_type'] = (int)$filters['property_type'];
        }

        if (!empty($filters['transaction_type'])) {
            $sql .= " AND tratyp.id = :transaction_type";
            $params[':transaction_type'] = (int)$filters['transaction_type'];
        }

        $sql .= " ORDER BY f.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFavorites(int $userId, array $filters = []): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM favorite AS f
            JOIN listing AS lis ON f.listing_id = lis.id
            JOIN propertytype AS protyp ON lis.property_type_id = protyp.id
            JOIN transactiontype AS tratyp ON lis.transaction_type_id = tratyp.id
            WHERE f.user_id = :user_id
        ";

        $params = [':user_id' => $userId];

        if (!empty($filters['city'])) {
            $sql .= " AND lis.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND lis.price <= :max_price";
            $params[':max_price'] = (int)$filters['max_price'];
        }

        if (!empty($filters['property_type'])) {
            $sql .= " AND protyp.id = :property_type";
            $params[':property_type'] = (int)$filters['property_type'];
        }

        if (!empty($filters['transaction_type'])) {
            $sql .= " AND tratyp.id = :transaction_type";
            $params[':transaction_type'] = (int)$filters['transaction_type'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // --- Dropdown lists ---
    public function getPropertyTypes(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM propertytype ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionTypes(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM transactiontype ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
