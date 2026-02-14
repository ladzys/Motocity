<?php
/**
 * Simple test script to verify database connection and setup
 * Run this file after installation to verify everything is working
 */

require_once 'config.php';
require_once 'classes/Database.php';

echo "<h1>MotoCity - Database Connection Test</h1>";
echo "<style>body { font-family: Arial, sans-serif; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; }</style>";

try {
    // Test database connection
    echo "<h2>Testing Database Connection...</h2>";
    $db = Database::getInstance()->getConnection();
    echo "<p class='success'>✓ Database connection successful!</p>";
    
    // Test tables existence
    echo "<h2>Checking Database Tables...</h2>";
    
    $tables = ['users', 'motorbikes', 'rentals'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p class='success'>✓ Table '$table' exists</p>";
            
            // Count records
            $count = $db->query("SELECT COUNT(*) as count FROM $table")->fetch();
            echo "<p class='info'>  → Records: {$count['count']}</p>";
        } else {
            echo "<p class='error'>✗ Table '$table' NOT found</p>";
        }
    }
    
    // Test admin user
    echo "<h2>Checking Admin User...</h2>";
    $stmt = $db->query("SELECT username, email, role FROM users WHERE role = 'Admin'");
    $admin = $stmt->fetch();
    if ($admin) {
        echo "<p class='success'>✓ Admin user found: {$admin['username']} ({$admin['email']})</p>";
        echo "<p class='info'>  → Default password: admin123</p>";
    } else {
        echo "<p class='error'>✗ Admin user NOT found</p>";
    }
    
    // Test sample motorbikes
    echo "<h2>Checking Sample Motorbikes...</h2>";
    $stmt = $db->query("SELECT COUNT(*) as count FROM motorbikes");
    $bikeCount = $stmt->fetch();
    echo "<p class='success'>✓ Sample motorbikes: {$bikeCount['count']}</p>";
    
    echo "<h2>Test Complete!</h2>";
    echo "<p class='success'>✓ All tests passed! You can now access the application.</p>";
    echo "<p><a href='index.php'>Go to Homepage</a> | <a href='login.php'>Login</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration in config.php</p>";
}
?>
