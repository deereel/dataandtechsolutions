<?php
/**
 * Data Tutors - Payment Callback
 * Handle successful payment verification from Paystack
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Get reference from query params
$reference = $_GET['reference'] ?? '';

if (!$reference) {
    redirect(APP_URL . '/dashboard/index.php');
}

// Verify payment
$verification = Payment::verify($reference);

if ($verification && $verification['status'] === 'success') {
    // Get payment metadata
    $metadata = $verification['data']['metadata'] ?? [];
    $courseId = $metadata['course_id'] ?? 0;
    $userId = $metadata['user_id'] ?? 0;
    
    if ($courseId && $userId) {
        // Check if enrollment already exists
        $existing = DatabaseConnection::fetchOne(
            "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?",
            [$userId, $courseId]
        );
        
        if (!$existing) {
            // Create enrollment
            DatabaseConnection::query(
                "INSERT INTO enrollments (user_id, course_id, payment_status, payment_reference, created_at) VALUES (?, ?, 'completed', ?, NOW())",
                [$userId, $courseId, $reference]
            );
            
            // Record payment
            DatabaseConnection::query(
                "INSERT INTO payments (user_id, course_id, amount, reference, status, created_at) VALUES (?, ?, ?, ?, 'completed', NOW())",
                [$userId, $courseId, $verification['data']['amount'] / 100, $reference]
            );
        }
        
        $_SESSION['success'] = 'Payment successful! You are now enrolled in the course.';
        redirect(APP_URL . '/course/details.php?id=' . $courseId);
    }
}

$_SESSION['error'] = 'Payment verification failed. Please contact support.';
redirect(APP_URL . '/payment/failed.php');
