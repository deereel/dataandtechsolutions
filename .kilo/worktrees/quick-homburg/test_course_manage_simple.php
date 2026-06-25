<?php
/**
 * Simple test file to verify course-manage.php functionality without authentication
 */

// Skip authentication for testing
define('SKIP_AUTH', true);

// Include course-manage.php without authentication checks
ob_start();
include 'admin/course-manage.php';
$content = ob_get_clean();

// Check if page loaded successfully
if (strpos($content, 'Manage Course:') !== false) {
    echo '<h1>SUCCESS: Course Manage Page Loaded</h1>';
    echo '<p>The page is rendering correctly. The clicking issue might be due to:</p>';
    echo '<ul>';
    echo '<li>JavaScript errors in the browser console</li>';
    echo '<li>CSS issues preventing button clicks</li>';
    echo '<li>Authentication issues</li>';
    echo '</ul>';
    
    // Display a snippet of the content
    echo '<h2>Page Snippet:</h2>';
    echo '<pre style="background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: scroll;">';
    echo htmlentities(substr($content, 0, 500));
    echo '...';
    echo '</pre>';
} else {
    echo '<h1>ERROR: Page Failed to Load</h1>';
    echo '<pre style="background: #ffebee; padding: 10px; border-radius: 5px; color: red;">';
    echo htmlentities($content);
    echo '</pre>';
}
?>