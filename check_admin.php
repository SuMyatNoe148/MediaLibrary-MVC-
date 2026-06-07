<?php
require_once __DIR__ . '/config/DatabaseConnection.php';

try {
    $db = Database::getConnection();
    
    // Check for admin users
    $stmt = $db->query("SELECT user_id, username, is_admin FROM Users WHERE is_admin = 1");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Admin users found: " . count($admins) . "\n";
    foreach ($admins as $admin) {
        echo "ID: {$admin['user_id']}, Username: {$admin['username']}, Is Admin: {$admin['is_admin']}\n";
    }
    
    // Check if there are any users at all
    $stmt = $db->query("SELECT COUNT(*) as count FROM Users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal users: " . $result['count'] . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
