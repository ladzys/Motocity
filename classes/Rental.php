<?php
/**
 * Rental class - Handles rental operations
 */
class Rental {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create new rental
     * @param int $userId
     * @param int $motorbikeId
     * @param string $startDatetime
     * @return bool|string True on success, error message on failure
     */
    public function create($userId, $motorbikeId, $startDatetime) {
        // Validate inputs
        if (empty($userId) || empty($motorbikeId) || empty($startDatetime)) {
            return "User ID, motorbike ID, and start datetime are required";
        }
        
        // Check if motorbike exists and is available
        $stmt = $this->db->prepare("SELECT availability FROM motorbikes WHERE id = ?");
        $stmt->execute([$motorbikeId]);
        $motorbike = $stmt->fetch();
        
        if (!$motorbike) {
            return "Motorbike not found";
        }
        
        if ($motorbike['availability'] !== 'Available') {
            return "Motorbike is not available";
        }
        
        // Validate datetime format (HTML5 datetime-local uses T separator)
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $startDatetime);
        // Try alternative format without T separator
        if (!$dateTime) {
            $dateTime = DateTime::createFromFormat('Y-m-d H:i', $startDatetime);
        }
        if (!$dateTime) {
            return "Invalid datetime format. Use YYYY-MM-DD HH:MM";
        }
        
        // Check if datetime is in the future
        if ($dateTime < new DateTime()) {
            return "Start datetime must be in the future";
        }
        
        // Insert rental
        $stmt = $this->db->prepare("
            INSERT INTO rentals (user_id, motorbike_id, start_datetime, status) 
            VALUES (?, ?, ?, 'Active')
        ");
        
        try {
            $this->db->beginTransaction();
            
            // Insert rental
            $stmt->execute([$userId, $motorbikeId, $startDatetime]);
            
            // Update motorbike availability
            $updateStmt = $this->db->prepare("UPDATE motorbikes SET availability = 'Rented' WHERE id = ?");
            $updateStmt->execute([$motorbikeId]);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Failed to create rental: " . $e->getMessage();
        }
    }
    
    /**
     * Return rental (complete rental with cost calculation)
     * @param int $rentalId
     * @return bool|string True on success, error message on failure
     */
    public function returnRental($rentalId) {
        // Get rental details
        $stmt = $this->db->prepare("
            SELECT r.*, m.price_per_day 
            FROM rentals r
            JOIN motorbikes m ON r.motorbike_id = m.id
            WHERE r.id = ? AND r.status = 'Active'
        ");
        $stmt->execute([$rentalId]);
        $rental = $stmt->fetch();
        
        if (!$rental) {
            return "Rental not found or already completed";
        }
        
        // Calculate rental duration and cost
        $startDatetime = new DateTime($rental['start_datetime']);
        $endDatetime = new DateTime();
        $interval = $startDatetime->diff($endDatetime);
        
        // Calculate days (including partial days)
        $days = $interval->days;
        $hours = $interval->h;
        
        // If there are any hours, count as an additional day
        if ($hours > 0 || $interval->i > 0) {
            $days++;
        }
        
        // Minimum rental is 1 day
        if ($days < 1) {
            $days = 1;
        }
        
        $totalCost = $days * $rental['price_per_day'];
        
        // Update rental
        try {
            $this->db->beginTransaction();
            
            // Update rental status
            $stmt = $this->db->prepare("
                UPDATE rentals 
                SET end_datetime = NOW(), total_cost = ?, status = 'Completed'
                WHERE id = ?
            ");
            $stmt->execute([$totalCost, $rentalId]);
            
            // Update motorbike availability
            $updateStmt = $this->db->prepare("UPDATE motorbikes SET availability = 'Available' WHERE id = ?");
            $updateStmt->execute([$rental['motorbike_id']]);
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return "Failed to return rental: " . $e->getMessage();
        }
    }
    
    /**
     * Get all rentals
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT r.*, u.username, m.brand, m.model 
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            JOIN motorbikes m ON r.motorbike_id = m.id
            ORDER BY r.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Get rentals by user ID
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, m.brand, m.model, m.price_per_day
            FROM rentals r
            JOIN motorbikes m ON r.motorbike_id = m.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get rental by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username, m.brand, m.model, m.price_per_day
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            JOIN motorbikes m ON r.motorbike_id = m.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get active rentals
     * @return array
     */
    public function getActive() {
        $stmt = $this->db->query("
            SELECT r.*, u.username, m.brand, m.model 
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            JOIN motorbikes m ON r.motorbike_id = m.id
            WHERE r.status = 'Active'
            ORDER BY r.start_datetime DESC
        ");
        return $stmt->fetchAll();
    }
}
