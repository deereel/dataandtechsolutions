<?php
/**
 * Test script to debug lesson reordering
 */
require_once 'config/config.php';
require_once 'config/database.php';

// Get all courses
$courses = DatabaseConnection::fetchAll("SELECT * FROM courses");
echo "Available Courses:\n";
foreach ($courses as $course) {
    echo "ID: {$course['id']} - Title: {$course['title']}\n";
}

// Test with first course
if (!empty($courses)) {
    $courseId = $courses[0]['id'];
    
    // Get modules for course
    $modules = Module::getAllByCourse($courseId);
    echo "\nModules for Course {$courseId}:\n";
    foreach ($modules as $module) {
        echo "ID: {$module['id']} - Title: {$module['title']}\n";
        
        // Get lessons for module
        $lessons = Lesson::getAllByModule($module['id']);
        echo "Lessons:\n";
        foreach ($lessons as $lesson) {
            echo "  ID: {$lesson['id']} - Title: {$lesson['title']} - Order Index: {$lesson['order_index']}\n";
        }
        
        // Test updateOrder method
        if (!empty($lessons)) {
            echo "\nTesting updateOrder for module {$module['id']}:\n";
            
            // Reverse the order of lessons
            $reverseOrder = array_reverse($lessons);
            foreach ($reverseOrder as $index => $lesson) {
                $newOrder = $index + 1;
                echo "  Updating lesson {$lesson['id']} to order {$newOrder}...\n";
                $result = Lesson::updateOrder($lesson['id'], $newOrder);
                echo "  Success: " . ($result ? "Yes" : "No") . "\n";
            }
            
            // Verify changes
            $updatedLessons = Lesson::getAllByModule($module['id']);
            echo "\nUpdated Lessons:\n";
            foreach ($updatedLessons as $lesson) {
                echo "  ID: {$lesson['id']} - Title: {$lesson['title']} - Order Index: {$lesson['order_index']}\n";
            }
            
            // Restore original order
            echo "\nRestoring original order...\n";
            foreach ($lessons as $index => $lesson) {
                $originalOrder = $index + 1;
                Lesson::updateOrder($lesson['id'], $originalOrder);
            }
            
            // Verify restore
            $restoredLessons = Lesson::getAllByModule($module['id']);
            echo "\nRestored Lessons:\n";
            foreach ($restoredLessons as $lesson) {
                echo "  ID: {$lesson['id']} - Title: {$lesson['title']} - Order Index: {$lesson['order_index']}\n";
            }
        }
        echo "\n" . str_repeat("-", 50) . "\n";
    }
} else {
    echo "\nNo courses found in database.\n";
}
