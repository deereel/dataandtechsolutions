<?php
/**
 * Newsletter Subscription Handler
 */

// Include config
require_once 'config/config.php';
require_once 'config/database.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = 'Please enter your email address';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        try {
            // Check if email already exists in newsletter subscribers (you'll need to create this table)
            $stmt = DatabaseConnection::query("SELECT id FROM newsletter_subscribers WHERE email = ?", [$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'This email is already subscribed';
            } else {
                // Insert new subscriber
                DatabaseConnection::query("INSERT INTO newsletter_subscribers (email, created_at) VALUES (?, NOW())", [$email]);
                
                $success = true;
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again later.';
            error_log('Newsletter subscription error: ' . $e->getMessage());
        }
    }
}

// Redirect back with status
if ($success) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?subscription=success');
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?subscription=error&message=' . urlencode($error));
}
exit;
