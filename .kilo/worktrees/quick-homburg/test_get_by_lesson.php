<?php
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h1>Testing LessonResource::getByLesson()</h1>";

$lessonId = 519;
$resources = LessonResource::getByLesson($lessonId);

echo "<p>Found " . count($resources) . " resources for lesson $lessonId</p>";

if (count($resources) > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th>ID</th>";
    echo "<th>Title</th>";
    echo "<th>File Path</th>";
    echo "<th>File Type</th>";
    echo "<th>Created At</th>";
    echo "</tr>";
    
    foreach ($resources as $resource) {
        echo "<tr>";
        echo "<td>" . $resource['id'] . "</td>";
        echo "<td>" . $resource['title'] . "</td>";
        echo "<td>" . $resource['file_path'] . "</td>";
        echo "<td>" . $resource['file_type'] . "</td>";
        echo "<td>" . $resource['created_at'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

// Check if the resource we just created exists
echo "<h2>Checking if resource ID 5 exists...</h2>";
$resource = LessonResource::getById(5);
if ($resource) {
    echo "<p style='color: green;'>✓ Resource ID 5 exists</p>";
    echo "<pre>";
    print_r($resource);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>✗ Resource ID 5 not found</p>";
}
?>
