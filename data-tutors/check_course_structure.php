<?php
/**
 * Check Course Structure Script
 * This script verifies the Excel course structure in the database
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Excel Course Structure Check ===\n\n";
    
    // Check course
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = 1");
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($course) {
        echo "Course: " . $course['title'] . "\n";
        echo "Description: " . substr($course['description'], 0, 100) . "...\n";
        echo "Price: $" . $course['price'] . "\n";
        echo "Duration: " . $course['duration_hours'] . " hours\n\n";
    }
    
    // Check modules
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE course_id = 1 ORDER BY order_index");
    $stmt->execute();
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total Modules: " . count($modules) . "\n\n";
    
    foreach ($modules as $module) {
        echo "Module " . $module['order_index'] . ": " . $module['title'] . "\n";
        echo "Description: " . substr($module['description'], 0, 100) . "...\n";
        
        // Check lessons in module
        $lessonStmt = $pdo->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY order_index");
        $lessonStmt->execute([$module['id']]);
        $lessons = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Lessons: " . count($lessons) . "\n";
        
        foreach ($lessons as $lesson) {
            echo "  - " . $lesson['title'] . " (" . ($lesson['video_duration'] ? round($lesson['video_duration']/60) . " min" : "no video") . ")\n";
        }
        
        echo "\n";
    }
    
    // Check quizzes
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE course_id = 1 ORDER BY id");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total Quizzes: " . count($quizzes) . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
