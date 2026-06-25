<?php
/**
 * Data Tutors - Payment Page
 * Course enrollment payment with Paystack integration
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Get course ID
$courseId = intval($_GET['course'] ?? 0);

if (!$courseId) {
    redirect(APP_URL . '/course/index.php');
}

// Fetch course
$course = DatabaseConnection::fetchOne("SELECT * FROM courses WHERE id = ? AND published = 1", [$courseId]);

if (!$course) {
    redirect(APP_URL . '/course/index.php');
}

// Check if already enrolled
if (isLoggedIn()) {
    $existingEnrollment = DatabaseConnection::fetchOne(
        "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?",
        [$_SESSION['user_id'], $courseId]
    );
    if ($existingEnrollment) {
        redirect(APP_URL . '/course/details.php?id=' . $courseId);
    }
}

// Handle payment initialization
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pay') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect(APP_URL . '/auth/login.php');
    }
    
    $email = $_SESSION['user_email'] ?? '';
    $amount = floatval($course['price']) * 100; // Convert to kobo
    
    // Initialize Paystack payment
    $payment = Payment::initialize([
        'email' => $email,
        'amount' => $amount,
        'reference' => Payment::generateReference(),
        'metadata' => [
            'course_id' => $courseId,
            'user_id' => $_SESSION['user_id']
        ]
    ]);
    
    if ($payment) {
        redirect($payment['authorization_url']);
    } else {
        $error = 'Failed to initialize payment. Please try again.';
    }
}

define('PAGE_TITLE', 'Enroll in ' . $course['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .payment-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--gray-50);
        }
        .payment-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }
        .payment-summary {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }
        .summary-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .course-summary {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .course-thumb {
            width: 100px;
            height: 70px;
            border-radius: var(--radius);
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .course-thumb svg {
            width: 40px;
            height: 40px;
            color: var(--gray-400);
        }
        .course-info h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .course-meta {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .price-breakdown {
            border-top: 1px solid var(--gray-100);
            padding-top: 1rem;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .price-row.total {
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-100);
        }
        .payment-form {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }
        .payment-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .payment-method {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }
        .payment-method:hover,
        .payment-method.active {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        .payment-method svg {
            width: 32px;
            height: 32px;
            margin-bottom: 0.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .secure-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: rgba(16, 185, 129, 0.1);
            border-radius: var(--radius);
            margin-top: 1rem;
            font-size: 0.875rem;
            color: var(--success);
        }
        .terms {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 1rem;
        }
        .terms a {
            color: var(--primary);
        }
        @media (max-width: 768px) {
            .payment-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="payment-page">
        <div class="payment-container">
            <!-- Payment Form -->
            <div class="payment-form">
                <h2 class="payment-title">Complete Your Payment</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= sanitize($error) ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="action" value="pay">
                    
                    <div class="payment-methods">
                        <div class="payment-method active">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <div>Card</div>
                        </div>
                        <div class="payment-method">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <div>Bank Transfer</div>
                        </div>
                        <div class="payment-method">
                            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <div>USSD</div>
                        </div>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" value="<?= sanitize($_SESSION['user_email']) ?>" class="form-control" readonly>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        Pay ₦<?= number_format($course['price'], 2) ?> with Paystack
                    </button>
                    
                    <div class="secure-badge">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Secure payment powered by Paystack
                    </div>
                    
                    <p class="terms">
                        By completing this purchase, you agree to our 
                        <a href="#">Terms of Service</a> and <a href="#">Refund Policy</a>.
                    </p>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="payment-summary">
                <h3 class="summary-title">Order Summary</h3>
                
                <div class="course-summary">
                    <div class="course-thumb">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="course-info">
                        <h3><?= sanitize($course['title']) ?></h3>
                        <div class="course-meta">
                            <?= ucfirst($course['category']) ?> • <?= ucfirst($course['level']) ?> Level
                        </div>
                    </div>
                </div>
                
                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Course Price</span>
                        <span>₦<?= number_format($course['price'], 2) ?></span>
                    </div>
                    <div class="price-row">
                        <span>Duration</span>
                        <span><?= $course['duration_hours'] ?> hours</span>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <span>₦<?= number_format($course['price'], 2) ?></span>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius);">
                    <h4 style="font-size: 0.875rem; margin-bottom: 0.5rem;">What's Included:</h4>
                    <ul style="font-size: 0.875rem; color: var(--gray-600); padding-left: 1.25rem;">
                        <li>Full lifetime access</li>
                        <li>Certificate of completion</li>
                        <li>Access to Q&A forum</li>
                        <li>All course materials</li>
                        <li>Mobile & desktop access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
