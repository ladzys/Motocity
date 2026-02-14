<?php
/**
 * User class - Handles user authentication and authorization
 */
class User {
    private $db;
    private $id;
    private $username;
    private $email;
    private $role;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Register a new user
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $role
     * @return bool|string True on success, error message on failure
     */
    public function register($username, $email, $password, $role = 'User') {
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            return "All fields are required";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        
        if (strlen($password) < 6) {
            return "Password must be at least 6 characters";
        }
        
        if (!in_array($role, ['Admin', 'User'])) {
            $role = 'User';
        }
        
        // Check if username or email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return "Username or email already exists";
        }
        
        // Hash password and insert user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        
        try {
            $stmt->execute([$username, $email, $hashedPassword, $role]);
            return true;
        } catch (PDOException $e) {
            return "Registration failed: " . $e->getMessage();
        }
    }
    
    /**
     * Login user
     * @param string $username
     * @param string $password
     * @return bool|string True on success, error message on failure
     */
    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            return "Username and password are required";
        }
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            return true;
        }
        
        return "Invalid username or password";
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }
    
    /**
     * Check if user is logged in
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Check if user is admin
     * @return bool
     */
    public static function isAdmin() {
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }
    
    /**
     * Get current user ID
     * @return int|null
     */
    public static function getCurrentUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Get current username
     * @return string|null
     */
    public static function getCurrentUsername() {
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }
    
    /**
     * Get current user role
     * @return string|null
     */
    public static function getCurrentUserRole() {
        return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            // Calculate path to login.php from current directory
            $path = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false) 
                ? '../login.php' 
                : 'login.php';
            header("Location: $path");
            exit();
        }
    }
    
    /**
     * Require admin role (redirect if not admin)
     */
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            // Redirect to parent directory index
            $path = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) 
                ? '../index.php' 
                : 'index.php';
            header("Location: $path");
            exit();
        }
    }
}
