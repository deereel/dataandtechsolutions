<?php
/**
 * Database Reset & Import Script
 * This will DROP all existing tables and recreate them
 */

// Database configuration
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = ''; // UPDATE YOUR PASSWORD HERE

echo "<h2>Database Import Status</h2>";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop existing tables (in reverse order due to foreign keys)
    $tables = [
        'activity_log',
        'notifications',
        'certificates',
        'payments',
        'forum_votes',
        'forum_answers',
        'forum_questions',
        'forum_categories',
        'quiz_results',
        'quiz_options',
        'quiz_questions',
        'quizzes',
        'user_lesson_progress',
        'user_course_progress',
        'enrollments',
        'lesson_resources',
        'lessons',
        'modules',
        'courses',
        'users',
        'settings'
    ];
    
    echo "<p>Dropping existing tables...</p>";
    foreach ($tables as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo "<span style='color: #10b981;'>✓ Dropped `$table`</span><br>";
        } catch (Exception $e) {
            echo "<span style='color: #f59e0b;'>⚠ Could not drop `$table`: " . $e->getMessage() . "</span><br>";
        }
    }
    
    // Read and execute the SQL file
    $sql = file_get_contents(__DIR__ . '/config/database.sql');
    
    // Split by DELIMITER statements and execute
    $statements = explode(';', $sql);
    
    echo "<p><strong>Creating tables...</strong></p>";
    $count = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && strpos($statement, 'CREATE TABLE') !== false) {
            $pdo->exec($statement);
            $count++;
            preg_match('/CREATE TABLE.*?`(\w+)`/', $statement, $matches);
            echo "<span style='color: #10b981;'>✓ Created `$matches[1]`</span><br>";
        }
    }
    
    echo "<br><h3 style='color: #10b981;'>✓ Successfully imported $count tables!</h3>";
    echo "<p><a href='index.php'>Go to Homepage →</a></p>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: #ef4444;'>Error: " . $e->getMessage() . "</h3>";
    echo "<p>Make sure to:</p>";
    echo "<ul>";
    echo "<li>Update your MySQL password in this file (line 11)</li>";
    echo "<li>Start your MySQL server (XAMPP/WAMP)</li>";
    echo "<li>Create the database: CREATE DATABASE data_tutors;</li>";
    echo "</ul>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Database - Data Tutors</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #f8fafc;
            line-height: 1.6;
        }
        h2 { color: #1e293b; margin-bottom: 1rem; }
        h3 { color: #334155; margin-top: 1.5rem; }
        p { color: #64748b; }
        a { color: #2563eb; }
        br { margin: 3px 0; }
    </style>
</head>
<body>
</body>
</html>
