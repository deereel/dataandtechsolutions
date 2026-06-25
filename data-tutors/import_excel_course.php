<?php
/**
 * Import Excel Course Final Curriculum
 * Run this file to import the updated Excel course curriculum
 */

// Database configuration - UPDATE THESE VALUES if needed
$host = 'localhost';
$dbname = 'data_tutors';
$username = 'root';
$password = ''; // Your MySQL password

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/excel_course_final.sql');
    
    // Execute the SQL
    $pdo->exec($sql);
    
    echo "<h1 style='color: #10b981;'>✓ Excel Course Imported Successfully!</h1>";
    echo "<p>The complete Excel course curriculum has been imported.</p>";
    echo "<p>12 Modules, 60+ Lessons, 12 Quizzes, 4 Mini Projects, 1 Capstone Project</p>";
    echo "<p><a href='index.php' style='color: #2563eb;'>Go to Homepage →</a></p>";
    
} catch (PDOException $e) {
    echo "<h1 style='color: #ef4444;'>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    
    // Common solutions
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL is running</li>";
    echo "<li>Check your database credentials in this file</li>";
    echo "<li>Make sure the database 'data_tutors' exists</li>";
    echo "<li>Check if you're using the correct MySQL port (default: 3306)</li>";
    echo "</ul>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel Course - Data Tutors</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8fafc;
        }
        h1 { margin-bottom: 1rem; }
        p { color: #64748b; }
        ul { color: #64748b; line-height: 1.8; }
    </style>
</head>
<body>
</body>
</html>
