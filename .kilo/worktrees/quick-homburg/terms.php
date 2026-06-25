<?php
/**
 * Data Tutors - Terms of Service
 * Terms and conditions for using Data Tutors website and services
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Terms of Service');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Data Tutors Terms of Service - Rules and guidelines for using our website and services.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .terms-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .terms-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .terms-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .terms-content {
            padding: 6rem 0;
            background: white;
        }

        .content-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .content-section {
            margin-bottom: 4rem;
        }

        .content-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--gray-200);
        }

        .content-section h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 2rem 0 1rem;
            color: var(--gray-900);
        }

        .content-section h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem;
            color: var(--gray-900);
        }

        .content-section p {
            color: var(--gray-600);
            line-height: 1.8;
            margin-bottom: 1rem;
            font-size: 1.0625rem;
        }

        .content-section ul,
        .content-section ol {
            margin: 1rem 0;
            padding-left: 2rem;
        }

        .content-section li {
            color: var(--gray-600);
            line-height: 1.8;
            margin-bottom: 0.5rem;
            font-size: 1.0625rem;
        }

        .content-section ul ul,
        .content-section ol ol {
            margin: 0.5rem 0;
            padding-left: 1.5rem;
        }

        .content-section strong {
            color: var(--gray-900);
            font-weight: 600;
        }

        .content-section em {
            font-style: italic;
        }

        .content-section a {
            color: var(--primary);
            text-decoration: none;
            border-bottom: 1px solid var(--primary);
            transition: var(--transition);
        }

        .content-section a:hover {
            color: var(--primary-dark);
            border-bottom-color: var(--primary-dark);
        }

        .highlight-box {
            background: var(--gray-50);
            border-left: 4px solid var(--primary);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: var(--radius);
        }

        .highlight-box p {
            margin-bottom: 0;
        }

        .last-updated {
            background: var(--gray-100);
            padding: 1.5rem;
            border-radius: var(--radius);
            text-align: center;
            margin-bottom: 3rem;
        }

        .last-updated strong {
            color: var(--primary);
            font-size: 1.125rem;
        }

        .contact-info {
            background: var(--primary);
            color: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            text-align: center;
            margin-top: 4rem;
        }

        .contact-info h3 {
            color: white;
            margin-bottom: 1rem;
        }

        .contact-info p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
        }

        .contact-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            transition: var(--transition);
        }

        .contact-button:hover {
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .terms-hero {
                padding: 4rem 0;
            }

            .terms-hero h1 {
                font-size: 2rem;
            }

            .terms-hero p {
                font-size: 1rem;
            }

            .terms-content {
                padding: 4rem 0;
            }

            .content-section h2 {
                font-size: 1.5rem;
            }

            .content-section h3 {
                font-size: 1.25rem;
            }

            .content-section h4 {
                font-size: 1.125rem;
            }

            .content-section p,
            .content-section li {
                font-size: 1rem;
            }

            .content-section ul,
            .content-section ol {
                padding-left: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="terms-hero">
        <div class="container">
            <h1>Terms of Service</h1>
            <p>Please read these Terms of Service carefully before using our website or services.</p>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="terms-content">
        <div class="content-container">
            <div class="last-updated">
                <p><strong>Last Updated:</strong> February 21, 2026</p>
            </div>

            <div class="content-section">
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing or using the Data Tutors website, courses, or services (collectively, "Services"), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, you may not use our Services.</p>
                <p>We reserve the right to modify these Terms at any time. We will notify you of any changes by posting the revised Terms on this page. Your continued use of the Services after changes are posted constitutes your acceptance of the revised Terms.</p>
            </div>

            <div class="content-section">
                <h2>2. Eligibility</h2>
                <p>You must be at least 18 years old to use our Services. By using our Services, you represent and warrant that you are at least 18 years old and have the legal capacity to enter into these Terms.</p>
            </div>

            <div class="content-section">
                <h2>3. Account Registration</h2>
                <p>To access certain features of our Services, you must register for an account. When you register, you agree to provide accurate, complete, and up-to-date information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>
                <p>We reserve the right to suspend or terminate your account if you provide false information or violate these Terms.</p>
            </div>

            <div class="content-section">
                <h2>4. Course Enrollment and Access</h2>
                <h3>4.1 Course Access</h3>
                <p>When you enroll in a course, you receive a non-exclusive, non-transferable license to access and use the course materials for your personal, non-commercial use. You may download course materials for offline viewing, but you may not share, distribute, or reproduce them without our written permission.</p>

                <h3>4.2 Account Sharing</h3>
                <p>You may not share your account credentials with others. Each account is intended for individual use only. Sharing accounts may result in account suspension or termination.</p>

                <h3>4.3 Course Completion</h3>
                <p>To earn a course certificate, you must complete all course requirements, including quizzes and assessments, with a minimum passing score. We reserve the right to determine what constitutes course completion.</p>
            </div>

            <div class="content-section">
                <h2>5. Payments and Refunds</h2>
                <h3>5.1 Payment Terms</h3>
                <p>All course prices are listed in USD. We accept various payment methods as indicated on our payment page. You agree to pay all fees and charges associated with your use of the Services.</p>

                <h3>5.2 Refund Policy</h3>
                <p>We offer a 30-day money-back guarantee. If you are not satisfied with a course, you may request a full refund within 30 days of purchase. Refunds will be processed using the original payment method.</p>
                <p>To be eligible for a refund, you must request it within 30 days of purchase and provide a valid reason. We reserve the right to deny refunds in cases of abuse or violation of these Terms.</p>

                <h3>5.3 Subscription and Renewal</h3>
                <p>Some of our services may be offered as subscriptions. Unless you cancel before the end of your billing period, your subscription will automatically renew, and your payment method will be charged for the next period.</p>
            </div>

            <div class="content-section">
                <h2>6. Intellectual Property</h2>
                <p>All content on our website, including but not limited to courses, videos, images, text, software, and other materials, is protected by copyright, trademark, and other intellectual property laws.</p>
                <p>You may not copy, modify, distribute, sell, or lease any part of our Services or included software. You may not reverse engineer or attempt to extract the source code of any software we provide, unless permitted by law.</p>
            </div>

            <div class="content-section">
                <h2>7. User Conduct</h2>
                <p>You agree to use our Services only for lawful purposes and in accordance with these Terms. You agree not to:</p>
                <ul>
                    <li>Use the Services in any way that violates applicable laws or regulations</li>
                    <li>Engage in any activity that disrupts or interferes with the Services</li>
                    <li>Upload, post, or transmit any content that is illegal, offensive, or violates the rights of others</li>
                    <li>Attempt to gain unauthorized access to our systems or accounts</li>
                    <li>Use the Services to spam, phish, or engage in other fraudulent activities</li>
                    <li>Share or distribute course materials without permission</li>
                </ul>
                <p>We reserve the right to suspend or terminate your account if you violate these terms.</p>
            </div>

            <div class="content-section">
                <h2>8. Disclaimer of Warranties</h2>
                <p>OUR SERVICES ARE PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT ANY WARRANTIES, EXPRESS OR IMPLIED. WE DISCLAIM ALL WARRANTIES, INCLUDING BUT NOT LIMITED TO MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.</p>
                <p>We do not guarantee that our Services will be error-free, secure, or available at all times. We reserve the right to modify, suspend, or discontinue any part of our Services at any time.</p>
            </div>

            <div class="content-section">
                <h2>9. Limitation of Liability</h2>
                <p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, DATA TUTORS AND ITS OFFICERS, DIRECTORS, EMPLOYEES, AND AGENTS SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, OR ANY LOSS OF PROFITS OR REVENUES, WHETHER INCURRED DIRECTLY OR INDIRECTLY, OR ANY LOSS OF DATA, USE, GOODWILL, OR OTHER INTANGIBLE LOSSES, RESULTING FROM YOUR ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE SERVICES.</p>
                <p>OUR TOTAL LIABILITY TO YOU FOR ALL CLAIMS ARISING OUT OF OR RELATING TO THE SERVICES SHALL NOT EXCEED THE AMOUNT YOU PAID US FOR THE SERVICES DURING THE 12-MONTH PERIOD PRECEDING THE CLAIM.</p>
            </div>

            <div class="content-section">
                <h2>10. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the State of California, without regard to its conflict of law principles.</p>
                <p>Any dispute arising out of or relating to these Terms shall be resolved through arbitration in accordance with the rules of the American Arbitration Association. Arbitration shall take place in San Francisco, California.</p>
            </div>

            <div class="content-section">
                <h2>11. Termination</h2>
                <p>We may terminate or suspend your access to our Services at any time, without prior notice or liability, for any reason, including without limitation if you breach these Terms.</p>
                <p>Upon termination, your right to use our Services will immediately cease. We may retain your account information and other data as required by law.</p>
            </div>

            <div class="content-section">
                <h2>12. Entire Agreement</h2>
                <p>These Terms constitute the entire agreement between you and Data Tutors regarding your use of the Services. These Terms supersede any prior agreements between you and Data Tutors.</p>
            </div>

            <div class="content-section">
                <h2>13. Contact Us</h2>
                <p>If you have any questions about these Terms of Service, please contact us at:</p>
                <div class="highlight-box">
                    <p><strong>Data Tutors</strong><br>
                    Email: legal@datatutors.com<br>
                    Address: 123 Education Street, Learning City, LC 12345</p>
                </div>

                <div class="contact-info">
                    <h3>Need Help?</h3>
                    <p>If you have questions about our Terms of Service, our support team is here to assist you.</p>
                    <a href="<?= APP_URL ?>/contact.php" class="contact-button">Contact Support</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>