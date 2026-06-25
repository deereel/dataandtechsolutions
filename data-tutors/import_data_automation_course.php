<?php
/**
 * Import Data Automation Course Curriculum
 * 
 * This script imports the complete Data Automation program into the database.
 * It follows the same structure as the Excel and Data Analysis courses with 
 * modules, lessons, and quizzes.
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Importing Data Automation Course Curriculum ===" . PHP_EOL;

try {
    // First, delete any existing modules, lessons, quizzes, and questions for course 10
    // This ensures we start with a clean slate
    echo "1. Cleaning up existing course content..." . PHP_EOL;
    
    // Delete quizzes and their questions first (due to foreign key constraints)
    $quizzes = DatabaseConnection::fetchAll('SELECT id FROM quizzes WHERE course_id = 10');
    foreach ($quizzes as $quiz) {
        DatabaseConnection::delete('quiz_options', 'question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = ?)', [$quiz['id']]);
        DatabaseConnection::delete('quiz_questions', 'quiz_id = ?', [$quiz['id']]);
        DatabaseConnection::delete('quizzes', 'id = ?', [$quiz['id']]);
    }
    echo "   ✅ Quizzes and questions deleted" . PHP_EOL;
    
    // Delete lessons
    $lessons = DatabaseConnection::fetchAll('SELECT id FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 10)');
    foreach ($lessons as $lesson) {
        DatabaseConnection::delete('lesson_resources', 'lesson_id = ?', [$lesson['id']]);
        DatabaseConnection::delete('lessons', 'id = ?', [$lesson['id']]);
    }
    echo "   ✅ Lessons deleted" . PHP_EOL;
    
    // Delete modules
    DatabaseConnection::delete('modules', 'course_id = ?', [10]);
    echo "   ✅ Modules deleted" . PHP_EOL;
    
    // Now import the complete curriculum from our SQL file
    echo "2. Importing complete Data Automation curriculum..." . PHP_EOL;
    
    // Read and execute the SQL file
    $sql = file_get_contents('data_automation_course.sql');
    if ($sql === false) {
        throw new Exception("Failed to read data_automation_course.sql file");
    }
    
    // Create a new PDO connection with buffered queries to avoid issues
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Execute the SQL in chunks (to avoid max packet size issues)
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
    
    echo "   ✅ Data Automation curriculum imported successfully" . PHP_EOL;
    
    // Verify the import by counting modules, lessons, and quizzes
    $moduleCount = $pdo->query('SELECT COUNT(*) FROM modules WHERE course_id = 10')->fetchColumn();
    $lessonCount = $pdo->query('SELECT COUNT(*) FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 10)')->fetchColumn();
    $quizCount = $pdo->query('SELECT COUNT(*) FROM quizzes WHERE course_id = 10')->fetchColumn();
    
    echo PHP_EOL;
    echo "=== Import Verification ===" . PHP_EOL;
    echo "📚 Modules: " . $moduleCount . PHP_EOL;
    echo "📖 Lessons: " . $lessonCount . PHP_EOL;
    echo "🎯 Quizzes: " . $quizCount . PHP_EOL;
    
    if ($moduleCount === 12 && $lessonCount > 40 && $quizCount === 9) {
        echo PHP_EOL;
        echo "✅ DATA AUTOMATION PROGRAM SUCCESSFULLY IMPORTED!" . PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo "🤖 Tools Covered: Zapier, Make.com, Airtable, N8N" . PHP_EOL;
        echo "⏱ Duration: 6-8 Weeks" . PHP_EOL;
        echo "🎯 Level: Beginner → Automation Specialist" . PHP_EOL;
        echo "🏆 Capstone Project Included" . PHP_EOL;
        echo "============================================" . PHP_EOL;
    } else {
        echo PHP_EOL;
        echo "⚠️  Import verification completed but numbers don't match expected values" . PHP_EOL;
        echo "   Expected: 12 modules, 40+ lessons, 9 quizzes" . PHP_EOL;
        echo "   Found: " . $moduleCount . " modules, " . $lessonCount . " lessons, " . $quizCount . " quizzes" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo PHP_EOL;
    echo "❌ Error importing Data Automation curriculum:" . PHP_EOL;
    echo "   " . $e->getMessage() . PHP_EOL;
    if (isset($e->errorInfo)) {
        echo "   SQL Error: " . print_r($e->errorInfo, true) . PHP_EOL;
    }
}
?>
