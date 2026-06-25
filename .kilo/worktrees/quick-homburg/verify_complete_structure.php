<?php
/**
 * Verify Complete Excel Course Structure
 * This script verifies the complete Excel course structure
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Complete Excel Course Structure Verification ===\n\n";
    
    // Check course details
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = 1");
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Course: " . $course['title'] . "\n";
    echo "Price: $" . $course['price'] . "\n";
    echo "Duration: " . $course['duration_hours'] . " hours\n";
    echo "Description: " . substr($course['description'], 0, 150) . "...\n\n";
    
    // Check modules
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE course_id = 1 ORDER BY order_index");
    $stmt->execute();
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total Modules: " . count($modules) . "\n";
    echo "Total Lessons: 0\n";
    echo "Total Quizzes: 0\n\n";
    
    $totalLessons = 0;
    $totalQuizzes = 0;
    
    foreach ($modules as $module) {
        echo "Module " . $module['order_index'] . ": " . $module['title'] . "\n";
        
        // Check lessons
        $lessonStmt = $pdo->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY order_index");
        $lessonStmt->execute([$module['id']]);
        $lessons = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "  Lessons: " . count($lessons) . "\n";
        $totalLessons += count($lessons);
        
        // Check quizzes
        $quizStmt = $pdo->prepare("SELECT * FROM quizzes WHERE module_id = ?");
        $quizStmt->execute([$module['id']]);
        $quizzes = $quizStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "  Quizzes: " . count($quizzes) . "\n";
        $totalQuizzes += count($quizzes);
        
        // Check if module has a quiz (should have one per module)
        if (count($quizzes) == 0) {
            echo "  ⚠️  No quiz found for this module\n";
        }
        
        echo "\n";
    }
    
    echo "=== Final Summary ===\n";
    echo "Total Modules: " . count($modules) . "\n";
    echo "Total Lessons: " . $totalLessons . "\n";
    echo "Total Quizzes: " . $totalQuizzes . "\n";
    
    // Check if we have all 12 main modules plus projects
    if (count($modules) == 17) {
        echo "✅ All 17 modules (12 main + 4 mini projects + 1 capstone) are present\n";
    }
    
    // Check if we have the correct number of lessons and quizzes
    if ($totalLessons == 111 && $totalQuizzes == 11) {
        echo "✅ Course structure is complete and correctly formatted\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
