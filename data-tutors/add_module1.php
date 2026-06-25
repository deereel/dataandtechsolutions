<?php
/**
 * Add Module 1: Getting Started with Excel
 * This script adds the first module to the Excel course
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Adding Module 1: Getting Started with Excel ===\n\n";
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/add_module1.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Module 1 added successfully!\n";
    
    // Verify the addition
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM modules WHERE course_id = 1 AND order_index = 1");
    $stmt->execute();
    $moduleCount = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE module_id = (SELECT id FROM modules WHERE course_id = 1 AND order_index = 1)");
    $stmt->execute();
    $lessonCount = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM quizzes WHERE module_id = (SELECT id FROM modules WHERE course_id = 1 AND order_index = 1)");
    $stmt->execute();
    $quizCount = $stmt->fetchColumn();
    
    echo "\n=== Module 1 Summary ===\n";
    echo "Module Count: " . $moduleCount . "\n";
    echo "Lessons: " . $lessonCount . "\n";
    echo "Quizzes: " . $quizCount . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
