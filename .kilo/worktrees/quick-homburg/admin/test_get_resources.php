<?php
// Start session
session_start();

// Bypass authentication for testing
$_SESSION['admin_id'] = 1;
$_SESSION['admin_name'] = 'Test Admin';
$_SESSION['admin_email'] = 'admin@example.com';
$_SESSION['admin_role'] = 'admin';

// Simulate the GET request
$_GET['action'] = 'get_resources';
$_GET['lesson_id'] = 519;

ob_start();

// Include the file to trigger the endpoint
require 'course-manage.php';

$response = ob_get_clean();

echo "<h1>Testing get_resources action</h1>";

echo "<h2>Endpoint response...</h2>";
echo "<pre>";
print_r($response);
echo "</pre>";
?>
