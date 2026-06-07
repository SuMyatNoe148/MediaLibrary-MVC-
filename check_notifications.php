<?php
require_once __DIR__ . '/config/DatabaseConnection.php';

try {
    $db = Database::getConnection();
    
    // Check for notifications
    $stmt = $db->query("SELECT * FROM Notifications ORDER BY created_at DESC LIMIT 10");
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total notifications: " . count($notifications) . "\n\n";
    
    foreach ($notifications as $notif) {
        echo "ID: {$notif['notification_id']}\n";
        echo "User ID: {$notif['user_id']}\n";
        echo "Type: {$notif['type']}\n";
        echo "Title: {$notif['title']}\n";
        echo "Message: {$notif['message']}\n";
        echo "Is Read: " . ($notif['is_read'] ? 'Yes' : 'No') . "\n";
        echo "Created: {$notif['created_at']}\n";
        echo "---\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
