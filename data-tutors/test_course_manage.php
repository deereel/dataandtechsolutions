<?php
/**
 * Test Course Manage Page
 * Quick script to verify course-manage.php functionality
 */

require_once 'config/config.php';

echo "<h1>Course Manage Page Test</h1>";
echo "<p>Checking if course-manage.php exists...</p>";

$courseManagePath = 'admin/course-manage.php';
if (file_exists($courseManagePath)) {
    echo "<p style='color: green;'><strong>✓ File exists</strong></p>";
    
    // Check if it has our changes
    $content = file_get_contents($courseManagePath);
    
    // Check if Quill editor is added
    if (strpos($content, 'quill.snow.css') !== false && strpos($content, 'quill.js') !== false) {
        echo "<p style='color: green;'><strong>✓ Quill rich text editor resources added</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>✗ Quill rich text editor resources NOT found</strong></p>";
    }
    
    // Check if required attributes are removed
    if (strpos($content, 'required') !== false) {
        echo "<p style='color: yellow;'><strong>⚠ Warning: Still contains 'required' attributes</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>✓ Required attributes removed</strong></p>";
    }
    
    // Check if Quill containers are present
    $quillContainers = [
        'addModuleDescription',
        'editModuleDescription', 
        'addLessonDescription',
        'addLessonContent',
        'editLessonDescription',
        'editLessonContent',
        'addQuizDescription',
        'editQuizDescription',
        'addQuestionText',
        'addQuestionExplanation'
    ];
    
    echo "<h3>Quill Containers:</h3>";
    foreach ($quillContainers as $container) {
        if (strpos($content, $container) !== false) {
            echo "<p style='color: green;'>✓ $container</p>";
        } else {
            echo "<p style='color: red;'>✗ $container</p>";
        }
    }
    
    // Check if hidden fields are present for Quill content
    $hiddenFields = [
        'addModuleDescriptionHidden',
        'editModuleDescriptionHidden',
        'addLessonDescriptionHidden',
        'addLessonContentHidden',
        'editLessonDescriptionHidden',
        'editLessonContentHidden',
        'addQuizDescriptionHidden',
        'editQuizDescriptionHidden',
        'addQuestionTextHidden',
        'addQuestionExplanationHidden'
    ];
    
    echo "<h3>Hidden Fields:</h3>";
    foreach ($hiddenFields as $field) {
        if (strpos($content, $field) !== false) {
            echo "<p style='color: green;'>✓ $field</p>";
        } else {
            echo "<p style='color: red;'>✗ $field</p>";
        }
    }
    
    // Check if server-side validation is removed
    $validationChecks = [
        'if (empty($title))',
        'if (empty($moduleId) || empty($title))',
        'if (empty($lessonId) || empty($title))',
        'if (empty($quizId) || empty($questionText) || empty($options))'
    ];
    
    echo "<h3>Server-side Validation:</h3>";
    foreach ($validationChecks as $check) {
        if (strpos($content, $check) === false) {
            echo "<p style='color: green;'>✓ $check (removed)</p>";
        } else {
            echo "<p style='color: red;'>✗ $check (still present)</p>";
        }
    }
    
} else {
    echo "<p style='color: red;'><strong>✗ File not found</strong></p>";
}

echo "<h2>Test Login Status:</h2>";
echo "<p><strong>Test user:</strong> test@example.com</p>";
echo "<p><strong>Password:</strong> test123</p>";
echo "<p><strong>Username:</strong> testadmin</p>";

echo "<h2>Quick Test:</h2>";
echo "<p>Try accessing the course management page directly:</p>";
echo "<p><a href='admin/course-manage.php?course_id=1' target='_blank'>Go to Course Management (Course ID: 1)</a></p>";
?>