<?php
/**
 * Data Tutors - Logout Handler
 */

require_once '../config/config.php';

// Check if user is logged in
if (isLoggedIn()) {
    // Log activity
    logActivity($_SESSION['user_id'], 'logout', 'users', $_SESSION['user_id']);
    
    // Destroy session
    session_destroy();
    
    // Clear remember cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// Redirect to homepage
redirect(APP_URL . '/index.php');
