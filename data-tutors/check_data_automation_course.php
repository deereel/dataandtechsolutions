<?php
/**
 * Check the current state of the Data Automation course
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Data Automation Course Current Status ===\n";

try {
    // Get course information
    $course = DatabaseConnection::fetch('SELECT * FROM courses WHERE id = 10');
    
    if (!$course) {
        echo "❌ Course not found with ID 10\n";
        exit(1);
    }
    
    echo "Course: " . $course['title'] . "\n";
    echo "ID: " . $course['id'] . "\n";
    echo "Category: " . $course['category'] . "\n";
    echo "Level: " . $course['level'] . "\n";
    echo "Price: $" . $course['price'] . "\n";
    echo "Duration: " . $course['duration_hours'] . " hours\n";
    echo "\n";
    
    // Get modules
    $modules = DatabaseConnection::fetchAll('SELECT * FROM modules WHERE course_id = 10 ORDER BY order_index');
    
    echo "=== Modules ===" . str_repeat("=", 45) . "\n";
    echo str_pad("Order", 8) . str_pad("Title", 45) . str_pad("Lessons", 10) . str_pad("Questions", 12) . "Type\n";
    echo str_repeat("-", 80) . "\n";
    
    $totalLessons = 0;
    $totalQuestions = 0;
    
    foreach ($modules as $module) {
        $lessonCount = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM lessons WHERE module_id = ?', [$module['id']]);
        $quizCount = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quizzes WHERE module_id = ?', [$module['id']]);
        
        $questionsCount = 0;
        if ($quizCount > 0) {
            $quizzes = DatabaseConnection::fetchAll('SELECT id FROM quizzes WHERE module_id = ?', [$module['id']]);
            foreach ($quizzes as $quiz) {
                $questionsCount += DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?', [$quiz['id']]);
            }
        }
        
        $totalLessons += $lessonCount;
        $totalQuestions += $questionsCount;
        
        $moduleType = "Learning Module";
        if (strpos($module['title'], 'Project') !== false) {
            $moduleType = "Project";
        } elseif (strpos($module['title'], 'Exam') !== false || strpos($module['title'], 'Assessment') !== false) {
            $moduleType = "Assessment";
        } elseif (strpos($module['title'], 'Quiz') !== false) {
            $moduleType = "Quiz";
        }
        
        echo str_pad($module['order_index'], 8) . 
             str_pad(substr($module['title'], 0, 43), 45) . 
             str_pad($lessonCount, 10) . 
             str_pad($questionsCount, 12) . 
             $moduleType . "\n";
    }
    
    echo "\n=== Summary ===" . str_repeat("=", 47) . "\n";
    echo str_pad("Total Modules:", 20) . count($modules) . "\n";
    echo str_pad("Total Lessons:", 20) . $totalLessons . "\n";
    echo str_pad("Total Questions:", 20) . $totalQuestions . "\n";
    echo str_pad("Quizzes:", 20) . DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quizzes WHERE course_id = 10') . "\n";
    
    echo "\n=== Verification ===" . str_repeat("=", 44) . "\n";
    
    // Check if we need to continue with manual import
    if (count($modules) == 0 || $totalLessons == 0 || $totalQuestions == 0) {
        echo "⚠️  Course content is missing. We'll need to import manually.\n";
    } else {
        echo "✅ Course content appears to be imported successfully!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    if (isset($e->errorInfo)) {
        echo "SQL Error: " . print_r($e->errorInfo, true);
    }
}
?>
