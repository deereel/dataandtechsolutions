<?php
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h1>Available Lessons</h1>";

try {
    $pdo = DatabaseConnection::getInstance();
    $stmt = $pdo->query("SELECT id, module_id, title FROM lessons");
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Found " . count($lessons) . " lessons</p>";
    
    if (count($lessons) > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f2f2f2;'>";
        echo "<th>ID</th>";
        echo "<th>Module ID</th>";
        echo "<th>Title</th>";
        echo "</tr>";
        
        foreach ($lessons as $lesson) {
            echo "<tr>";
            echo "<td>" . $lesson['id'] . "</td>";
            echo "<td>" . $lesson['module_id'] . "</td>";
            echo "<td>" . $lesson['title'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
