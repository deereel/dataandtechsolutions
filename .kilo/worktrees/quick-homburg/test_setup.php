<?php
/**
 * Data Tutors - Setup Verification Script
 * Run this file to verify your project setup is correct
 */

require_once 'config/config.php';
require_once 'config/database.php';

echo "<h1>Data Tutors - Setup Verification</h1>";

$tests = [];

// Test 1: Database Connection
try {
    $result = DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM users");
    $tests['Database Connection'] = $result !== false ? 'OK (' . $result . ' users)' : 'Failed';
} catch (Exception $e) {
    $tests['Database Connection'] = 'Error: ' . $e->getMessage();
}

// Test 2: Check Tables
$requiredTables = ['users', 'courses', 'modules', 'lessons', 'enrollments', 'user_course_progress', 'user_lesson_progress', 'quizzes', 'quiz_questions', 'forum_questions', 'forum_answers', 'certificates', 'payments'];
foreach ($requiredTables as $table) {
    try {
        $result = DatabaseConnection::fetchColumn("SHOW TABLES LIKE '$table'");
        $tests["Table: $table"] = $result ? 'Exists' : 'Missing';
    } catch (Exception $e) {
        $tests["Table: $table"] = 'Error';
    }
}

// Test 3: Check Files
$requiredFiles = [
    'config/config.php',
    'config/database.php',
    'includes/header.php',
    'includes/footer.php',
    'assets/css/styles.css',
    'assets/js/app.js',
    'pwa/manifest.json',
    'pwa/sw.js',
    'auth/login.php',
    'auth/register.php',
    'index.php',
    'course/index.php',
    'course/details.php',
    'course/lesson.php',
    'quiz/take.php',
    'quiz/results.php',
    'dashboard/index.php',
    'forum/index.php',
    'forum/question.php',
    'admin/index.php',
    'certificate.php',
    'payment.php',
];

foreach ($requiredFiles as $file) {
    $tests["File: $file"] = file_exists($file) ? 'Exists' : 'Missing';
}

// Output Results
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
.test { padding: 8px 0; border-bottom: 1px solid #eee; }
.pass { color: #10b981; }
.fail { color: #ef4444; }
h2 { color: #374151; margin-top: 30px; }
</style>";

echo "<h2>Database and Tables</h2>";
foreach ($tests as $name => $status) {
    $class = strpos($status, 'OK') !== false || strpos($status, 'Exists') !== false ? 'pass' : 'fail';
    echo "<div class='test $class'>$name: $status</div>";
}

// Summary
$passed = count(array_filter($tests, function($v) { return strpos($v, 'OK') !== false || strpos($v, 'Exists') !== false; }));
$total = count($tests);

echo "<h2>Summary</h2>";
echo "<p><strong>Passed:</strong> $passed / $total</p>";

if ($passed === $total) {
    echo "<p style='color: #10b981; font-weight: bold;'>All tests passed! Your setup is complete.</p>";
    echo "<p>Next steps:</p>";
    echo "<ul>";
    echo "<li>Configure your Paystack API keys in config/config.php</li>";
    echo "<li>Customize the design in assets/css/styles.css</li>";
    echo "<li>Add your own courses and content</li>";
    echo "</ul>";
} else {
    echo "<p style='color: #ef4444; font-weight: bold;'>Some tests failed. Please check the errors above.</p>";
}
