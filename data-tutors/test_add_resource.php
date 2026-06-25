<?php
require 'config/config.php';
require 'config/database.php';

// Simulate admin session
$_SESSION['admin_id'] = 1;
$_SESSION['admin_name'] = 'Test Admin';
$_SESSION['admin_email'] = 'admin@example.com';
$_SESSION['admin_role'] = 'admin';

echo "=== Testing Resource Addition ===\n\n";

$lessonId = 519;
$title = "Test Resource " . time();
$resourceType = "document";
$resourceUrl = "https://example.com/test.pdf";

echo "Adding resource:\n";
echo "Lesson ID: $lessonId\n";
echo "Title: $title\n";
echo "Type: $resourceType\n";
echo "URL: $resourceUrl\n\n";

// Simulate the resource creation
$resourceData = [
    'lesson_id' => $lessonId,
    'title' => $title,
    'file_path' => $resourceUrl,
    'file_type' => $resourceType
];

echo "Resource data:\n";
print_r($resourceData);
echo "\n";

try {
    $resourceId = LessonResource::create($resourceData);
    echo "✓ Resource created with ID: $resourceId\n\n";
    
    // Verify it was saved
    $resource = LessonResource::getById($resourceId);
    echo "Verification - Resource from DB:\n";
    print_r($resource);
    echo "\n";
    
    // Get all resources for this lesson
    $allResources = LessonResource::getByLesson($lessonId);
    echo "Total resources for lesson $lessonId: " . count($allResources) . "\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
