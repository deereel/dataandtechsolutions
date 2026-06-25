<?php
/**
 * Check Quiz IDs for Excel Course
 * This script checks the actual quiz IDs in the database
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Excel Course Quiz IDs ===\n\n";
    
    // Get all quizzes for course 1
    $stmt = $pdo->prepare("SELECT q.id, q.title, m.title as module_title 
                           FROM quizzes q 
                           LEFT JOIN modules m ON q.module_id = m.id 
                           WHERE q.course_id = 1 
                           ORDER BY m.order_index, q.id");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($quizzes as $quiz) {
        echo "Quiz ID: " . $quiz['id'] . "\n";
        echo "Title: " . $quiz['title'] . "\n";
        echo "Module: " . $quiz['module_title'] . "\n";
        
        // Check number of questions
        $questionStmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?");
        $questionStmt->execute([$quiz['id']]);
        $questionCount = $questionStmt->fetchColumn();
        echo "Questions: " . $questionCount . "\n\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
