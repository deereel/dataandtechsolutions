<?php
/**
 * Import Quiz Questions for Modules 1-5
 * This script imports 10 quiz questions per module for modules 1 to 5
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Importing Quiz Questions for Modules 1-5 ===\n\n";
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/quiz_questions_modules_1-5.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "Quiz questions imported successfully!\n";
    
    // Verify the import with actual quiz IDs
    $quizMap = [
        121 => 'Excel Basics Quiz (Module 1)',
        111 => 'Working with Data Quiz (Module 2)',
        112 => 'Basic Formulas Quiz (Module 3)',
        113 => 'Essential Functions Quiz (Module 4)',
        114 => 'Text & Dates Quiz (Module 5)'
    ];
    
    $totalQuestions = 0;
    foreach ($quizMap as $quizId => $quizTitle) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?");
        $stmt->execute([$quizId]);
        $questionCount = $stmt->fetchColumn();
        
        echo "Quiz $quizId ($quizTitle): " . $questionCount . " questions\n";
        $totalQuestions += $questionCount;
    }
    
    echo "\n=== Total Quiz Questions Imported: " . $totalQuestions . " ===\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
