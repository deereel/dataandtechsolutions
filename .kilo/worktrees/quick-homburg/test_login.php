<?php
// Test login to bypass real login (for debugging purposes only!)
session_start();

// Set session variables to bypass authentication
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

echo "Login successful! You are now logged in as admin.<br>";
echo "<a href='admin/course-manage.php?course_id=1'>Go to Course Manage Page</a>";
?>