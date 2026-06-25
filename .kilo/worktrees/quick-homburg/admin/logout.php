<?php
/**
 * Data Tutors - Admin Logout Handler
 * Properly logout admin from admin session
 */

require_once '../config/config.php';

// Check if admin is logged in
if (isAdminLoggedIn()) {
    // Log activity
    logActivity($_SESSION['admin_id'], 'admin_logout', 'admin', $_SESSION['admin_id']);
    
    // Destroy admin session
    logoutAdmin();
}

// Redirect to admin login page
redirect(APP_URL . '/admin/login.php');
