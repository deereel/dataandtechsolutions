<?php
/**
 * Simple Import for Data Analysis Course
 * 
 * This script uses a simpler approach to import the curriculum
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Importing Data Analysis Course Curriculum ===" . PHP_EOL;

try {
    // Create a new PDO connection with buffered queries
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ]
    );
    
    // Read and execute the SQL file
    $sql = file_get_contents('data_analysis_course.sql');
    if ($sql === false) {
        throw new Exception("Failed to read data_analysis_course.sql file");
    }
    
    // Split SQL into individual statements
    $statements = explode(';', $sql);
    
    echo "Executing SQL statements..." . PHP_EOL;
    $count = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !str_starts_with(strtolower($statement), '--') && !str_starts_with(strtolower($statement), '/*')) {
            try {
                $pdo->exec($statement);
                $count++;
                if ($count % 20 === 0) {
                    echo "   Executed " . $count . " statements..." . PHP_EOL;
                }
            } catch (Exception $e) {
                // Skip SELECT statements (for debugging)
                if (!str_starts_with(strtolower($statement), 'select')) {
                    echo "⚠️  Error with statement: " . substr($statement, 0, 100) . "..." . PHP_EOL;
                    echo "   Error: " . $e->getMessage() . PHP_EOL;
                }
            }
        }
    }
    
    echo PHP_EOL;
    echo "✅ Import completed!" . PHP_EOL;
    echo "   " . $count . " statements executed" . PHP_EOL;
    
    // Verify the import
    echo PHP_EOL;
    echo "=== Import Verification ===" . PHP_EOL;
    $moduleCount = $pdo->query('SELECT COUNT(*) FROM modules WHERE course_id = 9')->fetchColumn();
    $lessonCount = $pdo->query('SELECT COUNT(*) FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = 9)')->fetchColumn();
    $quizCount = $pdo->query('SELECT COUNT(*) FROM quizzes WHERE course_id = 9')->fetchColumn();
    
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
