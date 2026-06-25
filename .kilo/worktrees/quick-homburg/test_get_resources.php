<?php
// Bypass authentication for testing
$_SESSION['admin_id'] = 1;
$_SESSION['admin_logged_in'] = true;

// Include necessary files
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h1>Testing get_resources action</h1>";

$lessonId = 519;

echo "<h2>Getting resources for lesson $lessonId...</h2>";

// Call the getByLesson method directly
$resources = LessonResource::getByLesson($lessonId);

echo "<p>Found " . count($resources) . " resources</p>";

echo "<pre>";
print_r($resources);
echo "</pre>";

// Also test the get_resources endpoint logic
echo "<h2>Endpoint response...</h2>";

// Simulate the GET request
$_GET['action'] = 'get_resources';
$_GET['lesson_id'] = $lessonId;

ob_start();

// Include the file to trigger the endpoint
require 'admin/course-manage.php';

$response = ob_get_clean();

echo "<pre>";
print_r($response);
echo "</pre>";
?>
