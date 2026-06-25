<?php
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = DatabaseConnection::getInstance();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Check if database has any tables
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables in database: <strong>" . implode(', ', $tables) . "</strong></p>";
    
    // Check tables count
    echo "<p>Number of tables: " . count($tables) . "</p>";
    
    // Check if courses table exists
    if (in_array('courses', $tables)) {
        $stmt = $pdo->query('SELECT COUNT(*) FROM courses');
        $count = $stmt->fetchColumn();
        echo "<p style='color: blue;'>Courses in database: " . $count . "</p>";
        
        if ($count > 0) {
            $stmt = $pdo->query('SELECT id, title FROM courses LIMIT 5');
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Sample Courses:</h3>";
            echo "<ul>";
            foreach ($courses as $course) {
                echo "<li><strong>ID: " . $course['id'] . "</strong> - " . $course['title'] . "</li>";
            }
            echo "</ul>";
        }
    }
    
    // Check if lessons table exists
    if (in_array('lessons', $tables)) {
        $stmt = $pdo->query('SELECT COUNT(*) FROM lessons');
        $count = $stmt->fetchColumn();
        echo "<p style='color: blue;'>Lessons in database: " . $count . "</p>";
        
        if ($count > 0) {
            $stmt = $pdo->query('SELECT id, title, video_url, video_start, video_stop, video_duration FROM lessons LIMIT 3');
            $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Sample Lessons:</h3>";
            echo "<ul>";
            foreach ($lessons as $lesson) {
                echo "<li>";
                echo "<strong>ID: " . $lesson['id'] . "</strong><br>";
                echo "Title: " . $lesson['title'] . "<br>";
                echo "Video URL: " . $lesson['video_url'] . "<br>";
                echo "Video Start: " . $lesson['video_start'] . "s<br>";
                echo "Video Stop: " . $lesson['video_stop'] . "s<br>";
                echo "Video Duration: " . $lesson['video_duration'] . "s";
                echo "</li>";
            }
            echo "</ul>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>MySQL Driver Loaded: " . extension_loaded('pdo_mysql') ? "✅ Yes" : "❌ No" . "</p>";
?>