<?php
/**
 * Import Data Analysis Course Curriculum
 * 
 * This script imports the complete Data Analysis program into the database.
 * It follows the same structure as the Excel course with modules, lessons, and quizzes.
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Importing Data Analysis Course Curriculum ===" . PHP_EOL;

try {
    // First, delete any existing modules, lessons, quizzes, and questions for course 9
    // This ensures we start with a clean slate
    echo "1. Cleaning up existing course content..." . PHP_EOL;
    
    // Delete quizzes and their questions first (due to foreign key constraints)
    $quizzes = DatabaseConnection::fetchAll('SELECT id FROM quizzes WHERE course_id = 9');
    foreach ($quizzes as $quiz) {
        DatabaseConnection::delete('quiz_options', 'question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = ?)', [$quiz['id']]);
        DatabaseConnection::delete('quiz_questions', 'quiz_id = ?', [$quiz['id']]);
        DatabaseConnection::delete('quizzes', 'id = ?', [$quiz['id']]);
    }
    echo "   ✅ Quizzes and questions deleted" . PHP_EOL;
    
    // Delete lessons
    $lessons = DatabaseConnection::fetchAll('SELECT id FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 9)');
    foreach ($lessons as $lesson) {
        DatabaseConnection::delete('lesson_resources', 'lesson_id = ?', [$lesson['id']]);
        DatabaseConnection::delete('lessons', 'id = ?', [$lesson['id']]);
    }
    echo "   ✅ Lessons deleted" . PHP_EOL;
    
    // Delete modules
    DatabaseConnection::delete('modules', 'course_id = ?', [9]);
    echo "   ✅ Modules deleted" . PHP_EOL;
    
    // Now import the complete curriculum from our SQL file
    echo "2. Importing complete Data Analysis curriculum..." . PHP_EOL;
    
    // Read and execute the SQL file
    $sql = file_get_contents('data_analysis_course.sql');
    if ($sql === false) {
        throw new Exception("Failed to read data_analysis_course.sql file");
    }
    
    // Execute the SQL in chunks (to avoid max packet size issues)
    $pdo = DatabaseConnection::getInstance();
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !str_starts_with(strtolower($statement), 'select')) {
            try {
                $pdo->exec($statement);
            } catch (Exception $e) {
                // Skip SELECT statements (for debugging)
                if (!str_starts_with(strtolower($statement), 'select')) {
                    // Re-throw other exceptions
                    throw $e;
                }
            }
        }
    }
    
    echo "   ✅ Data Analysis curriculum imported successfully" . PHP_EOL;
    
    // Verify the import by counting modules, lessons, and quizzes
    $moduleCount = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM modules WHERE course_id = 9');
    $lessonCount = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 9)');
    $quizCount = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quizzes WHERE course_id = 9');
    
    echo PHP_EOL;
    echo "=== Import Verification ===" . PHP_EOL;
    echo "📚 Modules: " . $moduleCount . PHP_EOL;
    echo "📖 Lessons: " . $lessonCount . PHP_EOL;
    echo "🎯 Quizzes: " . $quizCount . PHP_EOL;
    
    if ($moduleCount === 17 && $lessonCount > 60 && $quizCount === 10) {
        echo PHP_EOL;
        echo "✅ DATA ANALYSIS PROGRAM SUCCESSFULLY IMPORTED!" . PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo "📊 Tools Covered: Excel, SQL, Python (Intro), Power BI" . PHP_EOL;
        echo "⏱ Duration: 10-14 Weeks" . PHP_EOL;
        echo "🎯 Level: Beginner → Job-Ready Analyst" . PHP_EOL;
        echo "🏆 Capstone Project Included" . PHP_EOL;
        echo "============================================" . PHP_EOL;
    } else {
        echo PHP_EOL;
        echo "⚠️  Import verification completed but numbers don't match expected values" . PHP_EOL;
        echo "   Expected: 17 modules, 60+ lessons, 10 quizzes" . PHP_EOL;
        echo "   Found: " . $moduleCount . " modules, " . $lessonCount . " lessons, " . $quizCount . " quizzes" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo PHP_EOL;
    echo "❌ Error importing Data Analysis curriculum:" . PHP_EOL;
    echo "   " . $e->getMessage() . PHP_EOL;
    if (isset($e->errorInfo)) {
        echo "   SQL Error: " . print_r($e->errorInfo, true) . PHP_EOL;
    }
}
?>
