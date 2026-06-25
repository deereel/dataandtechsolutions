<?php
require_once 'config/config.php';
require_once 'config/database.php';

try {
    $pdo = DatabaseConnection::getInstance();
    
    // Read SQL file
    $sql = file_get_contents('add_video_columns.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "✓ SQL executed successfully. Video columns added to lessons table.\n";
    
    // Verify columns exist
    $columns = [];
    $stmt = $pdo->query('SHOW COLUMNS FROM lessons');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $row['Field'];
    }
    
    echo "\nLessons table columns: " . implode(', ', $columns) . "\n";
    
    if (in_array('video_start', $columns)) {
        echo "✓ video_start column exists\n";
    }
    
    if (in_array('video_stop', $columns)) {
        echo "✓ video_stop column exists\n";
    }
    
    if (in_array('video_duration', $columns)) {
        echo "✓ video_duration column exists\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
