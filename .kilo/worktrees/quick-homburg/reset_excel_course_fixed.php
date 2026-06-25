<?php
/**
 * Reset Excel Course Structure (Fixed Version)
 * This script resets the Excel course to a clean state with proper auto-increment handling
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Resetting Excel Course Structure ===\n\n";
    
    // Delete existing modules, lessons, quizzes for course 1
    $stmt = $pdo->prepare("DELETE FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 1)");
    $stmt->execute();
    
    $stmt = $pdo->prepare("DELETE FROM quizzes WHERE course_id = 1");
    $stmt->execute();
    
    $stmt = $pdo->prepare("DELETE FROM modules WHERE course_id = 1");
    $stmt->execute();
    
    echo "Existing modules, lessons, and quizzes deleted.\n";
    
    // Re-import the correct structure with proper auto-increment handling
    $sql = file_get_contents(__DIR__ . '/excel_course_final_fixed.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Excel course structure re-imported successfully.\n";
    
    // Verify the import
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM modules WHERE course_id = 1");
    $stmt->execute();
    $moduleCount = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 1)");
    $stmt->execute();
    $lessonCount = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM quizzes WHERE course_id = 1");
    $stmt->execute();
    $quizCount = $stmt->fetchColumn();
    
    echo "\n=== Course Structure Summary ===\n";
    echo "Modules: " . $moduleCount . "\n";
    echo "Lessons: " . $lessonCount . "\n";
    echo "Quizzes: " . $quizCount . "\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
