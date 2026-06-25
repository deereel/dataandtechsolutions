<?php
// Test resource creation
require_once 'config/config.php';
require_once 'config/database.php';

echo "<h1>Testing LessonResource::create()</h1>";

// Test data
$testData = [
    'lesson_id' => 519,
    'title' => 'Test Resource',
    'file_path' => '/uploads/resources/test-file.pdf',
    'file_type' => 'document'
];

echo "<h2>Test Data:</h2>";
echo "<pre>";
print_r($testData);
echo "</pre>";

// Try to create resource
echo "<h2>Creating Resource...</h2>";
$resourceId = LessonResource::create($testData);

if ($resourceId) {
    echo "<p style='color: green;'>✓ Resource created successfully with ID: $resourceId</p>";
    
    // Get the created resource
    $createdResource = LessonResource::getById($resourceId);
    echo "<h2>Created Resource:</h2>";
    echo "<pre>";
    print_r($createdResource);
    echo "</pre>";
    
    // Test getByLesson
    echo "<h2>Testing getByLesson()...</h2>";
    $resources = LessonResource::getByLesson(1);
    echo "<p>Found " . count($resources) . " resources for lesson 1</p>";
    if (count($resources) > 0) {
        echo "<pre>";
        print_r($resources);
        echo "</pre>";
    }
} else {
    echo "<p style='color: red;'>✗ Failed to create resource</p>";
}

// Test the table exists
echo "<h2>Checking table structure...</h2>";
try {
    $pdo = DatabaseConnection::getInstance();
    $stmt = $pdo->query("SHOW TABLES LIKE 'lesson_resources'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ lesson_resources table exists</p>";
        
        $stmt = $pdo->query("DESCRIBE lesson_resources");
        echo "<h3>Table Structure:</h3>";
        echo "<pre>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>✗ lesson_resources table does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<h2>Testing file upload directory...</h2>";
$uploadDir = 'uploads/resources/';
if (is_dir($uploadDir)) {
    echo "<p style='color: green;'>✓ Upload directory exists: " . realpath($uploadDir) . "</p>";
    echo "<p>Permissions: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Upload directory does not exist: " . $uploadDir . "</p>";
    if (mkdir($uploadDir, 0755, true)) {
        echo "<p style='color: green;'>✓ Created directory: " . realpath($uploadDir) . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create directory</p>";
    }
}
?>
