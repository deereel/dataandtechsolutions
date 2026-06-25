<?php
require 'config/config.php';
require 'config/database.php';

echo "=== Testing Lesson Resources ===\n\n";

$lessonId = 519;

// Check if lesson exists
$lesson = Lesson::getById($lessonId);
if ($lesson) {
    echo "Lesson found: " . $lesson['title'] . "\n\n";
} else {
    echo "Lesson not found!\n";
    exit;
}

// Get resources for this lesson
$resources = LessonResource::getByLesson($lessonId);

echo "Resources found: " . count($resources) . "\n\n";

if (count($resources) > 0) {
    foreach ($resources as $resource) {
        echo "ID: " . $resource['id'] . "\n";
        echo "Title: " . $resource['title'] . "\n";
        echo "File Path: " . $resource['file_path'] . "\n";
        echo "File Type: " . $resource['file_type'] . "\n";
        echo "Created: " . $resource['created_at'] . "\n";
        echo "---\n";
    }
} else {
    echo "No resources found for this lesson.\n";
}

// Check the database table structure
echo "\n=== Table Structure ===\n";
$pdo = DatabaseConnection::getInstance();
$stmt = $pdo->query("DESCRIBE lesson_resources");
$columns = $stmt->fetchAll();
foreach ($columns as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}
?>
