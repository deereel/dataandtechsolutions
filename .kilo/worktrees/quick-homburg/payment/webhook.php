<?php
/**
 * Data Tutors - Paystack Webhook
 * Handle Paystack webhook events for payment verification
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Get raw POST data
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

// Verify signature
if (!Payment::verifyWebhook($signature, $payload)) {
    http_response_code(400);
    exit('Invalid signature');
}

$event = json_decode($payload, true);

if ($event['event'] === 'charge.success') {
    $data = $event['data'];
    $reference = $data['reference'];
    $metadata = $data['metadata'] ?? [];
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
                [$userId, $courseId, $data['amount'] / 100, $reference]
            );
            
            // Send confirmation email (optional)
            // Email::sendEnrollmentConfirmation($userId, $courseId);
        }
    }
}

http_response_code(200);
exit('Webhook processed');
