<?php
/**
 * Check the complete state of the Data Analysis course
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Data Analysis Course Complete Status ===\n";
echo "==========================================\n\n";

try {
    // Get course information
    $course = DatabaseConnection::fetch('SELECT * FROM courses WHERE id = 9');
    
    if (!$course) {
        echo "❌ Course not found with ID 9\n";
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
    $modules = DatabaseConnection::fetchAll('SELECT * FROM modules WHERE course_id = 9 ORDER BY order_index');
    
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
    echo str_pad("Quizzes with 10 questions:", 20) . DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM (SELECT q.id, COUNT(qq.id) as qcount FROM quizzes q LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id WHERE q.course_id = 9 GROUP BY q.id HAVING qcount = 10) as t') . "\n";
    
    echo "\n=== Verification ===" . str_repeat("=", 44) . "\n";
    
    // Verify all requirements
    $errors = [];
    
    if (count($modules) != 17) {
        $errors[] = "Expected 17 modules, found " . count($modules);
    }
    
    if ($totalLessons < 60) {
        $errors[] = "Expected at least 60 lessons, found " . $totalLessons;
    }
    
    if ($totalQuestions != 100) {
        $errors[] = "Expected exactly 100 questions, found " . $totalQuestions;
    }
    
    $quizzesWith10Questions = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM (SELECT q.id, COUNT(qq.id) as qcount FROM quizzes q LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id WHERE q.course_id = 9 GROUP BY q.id HAVING qcount = 10) as t');
    
    if ($quizzesWith10Questions != 10) {
        $errors[] = "Expected 10 quizzes with 10 questions each, found " . $quizzesWith10Questions;
    }
    
    if (empty($errors)) {
        echo "✅ All requirements met! The Data Analysis course is complete.\n";
        echo "\n📊 Course Overview:\n";
        echo "   - 17 Modules (including 5 Projects and 1 Capstone)\n";
        echo "   - " . $totalLessons . " Lessons covering Excel, SQL, Python, and Power BI\n";
        echo "   - 10 Quizzes with 10 questions each (100 total questions)\n";
        echo "   - Comprehensive curriculum following the same structure as the Excel course\n";
    } else {
        echo "⚠️  Course verification failed:\n";
        foreach ($errors as $error) {
            echo "   - " . $error . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    if (isset($e->errorInfo)) {
        echo "SQL Error: " . print_r($e->errorInfo, true);
    }
}
?>
