<?php
/**
 * Motorbike class - Handles motorbike CRUD operations
 */
class Motorbike {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all motorbikes
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM motorbikes ORDER BY brand, model");
        return $stmt->fetchAll();
    }
    
    /**
     * Get available motorbikes
     * @return array
     */
    public function getAvailable() {
        $stmt = $this->db->query("SELECT * FROM motorbikes WHERE availability = 'Available' ORDER BY brand, model");
        return $stmt->fetchAll();
    }
    
    /**
     * Search motorbikes by keyword (partial match)
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->prepare("
            SELECT * FROM motorbikes 
            WHERE brand LIKE ? OR model LIKE ? OR description LIKE ?
            ORDER BY brand, model
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get motorbike by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM motorbikes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Insert new motorbike
     * @param array $data
     * @return bool|string True on success, error message on failure
     */
    public function insert($data) {
        // Validate inputs
        if (empty($data['brand']) || empty($data['model']) || empty($data['year']) || empty($data['price_per_day'])) {
            return "Brand, model, year, and price per day are required";
        }
        
        if (!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > date('Y') + 1) {
            return "Invalid year";
        }
        
        if (!is_numeric($data['price_per_day']) || $data['price_per_day'] <= 0) {
            return "Invalid price per day";
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO motorbikes (brand, model, year, price_per_day, description) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([
                $data['brand'],
                $data['model'],
                $data['year'],
                $data['price_per_day'],
                $data['description'] ?? ''
            ]);
            return true;
        } catch (PDOException $e) {
            return "Failed to insert motorbike: " . $e->getMessage();
        }
    }
    
    /**
     * Update motorbike
     * @param int $id
     * @param array $data
     * @return bool|string True on success, error message on failure
     */
    public function update($id, $data) {
        // Validate inputs
        if (empty($data['brand']) || empty($data['model']) || empty($data['year']) || empty($data['price_per_day'])) {
            return "Brand, model, year, and price per day are required";
        }
        
        if (!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > date('Y') + 1) {
            return "Invalid year";
        }
        
        if (!is_numeric($data['price_per_day']) || $data['price_per_day'] <= 0) {
            return "Invalid price per day";
        }
        
        $stmt = $this->db->prepare("
            UPDATE motorbikes 
            SET brand = ?, model = ?, year = ?, price_per_day = ?, description = ?
            WHERE id = ?
        ");
        
        try {
            $stmt->execute([
                $data['brand'],
                $data['model'],
                $data['year'],
                $data['price_per_day'],
                $data['description'] ?? '',
                $id
            ]);
            return true;
        } catch (PDOException $e) {
            return "Failed to update motorbike: " . $e->getMessage();
        }
    }
    
    /**
     * Delete motorbike
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM motorbikes WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Update motorbike availability
     * @param int $id
     * @param string $availability
     * @return bool
     */
    public function updateAvailability($id, $availability) {
        $stmt = $this->db->prepare("UPDATE motorbikes SET availability = ? WHERE id = ?");
        return $stmt->execute([$availability, $id]);
    }
}
