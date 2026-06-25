<?php
require 'config/config.php';
require 'config/database.php';

echo "<h1>Checking Database Connection</h1>";

// Check connection
try {
    $pdo = DatabaseConnection::getInstance();
    echo "<p>✅ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
    die();
}

echo "<h2>Checking Courses Table</h2>";
$courses = Course::getAll();
echo "<p>Total courses: " . count($courses) . "</p>";

if (!empty($courses)) {
    echo "<h3>Course List:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Published</th><th>Featured</th><th>Created At</th></tr>";
    
    foreach ($courses as $course) {
        echo "<tr>";
        echo "<td>" . $course['id'] . "</td>";
        echo "<td>" . $course['title'] . "</td>";
        echo "<td>" . $course['slug'] . "</td>";
        echo "<td>" . ($course['published'] ? '✅' : '❌') . "</td>";
        echo "<td>" . ($course['featured'] ? '✅' : '❌') . "</td>";
        echo "<td>" . $course['created_at'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<h3>Testing Course::getById():</h3>";
    $testCourse = Course::getById(1);
    if ($testCourse) {
        echo "<p>✅ Course with ID 1 found: " . $testCourse['title'] . "</p>";
    } else {
        echo "<p>❌ Course with ID 1 not found</p>";
    }
    
} else {
    echo "<p>No courses found in the database. You may need to import a course.</p>";
    echo "<p>Available SQL files:</p>";
    $sqlFiles = glob('*.sql');
    if ($sqlFiles) {
        echo "<ul>";
        foreach ($sqlFiles as $file) {
            echo "<li>" . $file . "</li>";
        }
        echo "</ul>";
    }
}

echo "<h2>Checking Modules and Lessons</h2>";
echo "<p>For the first course (ID " . ($courses[0]['id'] ?? 'N/A') . "):</p>";
if (!empty($courses)) {
    $modules = Course::getModules($courses[0]['id']);
    echo "<p>Total modules: " . count($modules) . "</p>";
    
    foreach ($modules as $module) {
        $lessons = Module::getLessons($module['id']);
        echo "<p>Module \"" . $module['title'] . "\": " . count($lessons) . " lessons</p>";
    }
}
