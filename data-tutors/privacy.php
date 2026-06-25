<?php
/**
 * Data Tutors - Privacy Policy
 * Comprehensive privacy policy for Data Tutors website and services
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Privacy Policy');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Data Tutors Privacy Policy - Information about how we collect, use, and protect your personal data.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .privacy-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .privacy-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .privacy-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .privacy-content {
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
            .privacy-hero {
                padding: 4rem 0;
            }

            .privacy-hero h1 {
                font-size: 2rem;
            }

            .privacy-hero p {
                font-size: 1rem;
            }

            .privacy-content {
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
    <section class="privacy-hero">
        <div class="container">
            <h1>Privacy Policy</h1>
            <p>Your privacy is important to us. Learn how we collect, use, and protect your personal information.</p>
        </div>
    </section>

    <!-- Privacy Content -->
    <section class="privacy-content">
        <div class="content-container">
            <div class="last-updated">
                <p><strong>Last Updated:</strong> February 21, 2026</p>
            </div>

            <div class="content-section">
                <h2>1. Introduction</h2>
                <p>Welcome to Data Tutors ("we," "our," or "us"). We respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website, use our services, or interact with us in any way.</p>
                <p>Please read this Privacy Policy carefully. By using our website or services, you consent to the collection, use, and disclosure of your information as described in this policy. If you do not agree with our practices, please do not use our website or services.</p>
            </div>

            <div class="content-section">
                <h2>2. Information We Collect</h2>
                
                <h3>2.1 Information You Provide</h3>
                <p>We may collect personal information that you voluntarily provide to us when you:</p>
                <ul>
                    <li>Create an account or register for our services</li>
                    <li>Enroll in courses or purchase products</li>
                    <li>Sign up for our newsletter</li>
                    <li>Contact our support team</li>
                    <li>Participate in surveys, contests, or promotions</li>
                    <li>Submit feedback or comments</li>
                    <li>Post content on our forum or community</li>
                </ul>
                <p>This information may include your name, email address, postal address, phone number, payment information, and any other information you choose to provide.</p>

                <h3>2.2 Automatically Collected Information</h3>
                <p>When you visit our website, we may automatically collect certain information about your device and use of our services. This may include:</p>
                <ul>
                    <li>IP address</li>
                    <li>Browser type and version</li>
                    <li>Operating system</li>
                    <li>Device type</li>
                    <li>Location data (based on IP address)</li>
                    <li>Pages visited and time spent on each page</li>
                    <li>Referring website or search query</li>
                    <li>Clickstream data</li>
                    <li>Mobile device identifiers</li>
                </ul>
                <p>This information is collected using cookies, web beacons, and similar technologies. For more information about how we use cookies, please see our Cookie Policy.</p>
            </div>

            <div class="content-section">
                <h2>3. How We Use Your Information</h2>
                <p>We may use the information we collect for the following purposes:</p>
                <ul>
                    <li>To provide, maintain, and improve our services</li>
                    <li>To process your transactions and fulfill your orders</li>
                    <li>To create and manage your account</li>
                    <li>To send you course materials, updates, and notifications</li>
                    <li>To communicate with you about your account or purchases</li>
                    <li>To respond to your questions and provide customer support</li>
                    <li>To personalize your learning experience</li>
                    <li>To send you marketing communications (with your consent)</li>
                    <li>To analyze usage trends and improve our content and services</li>
                    <li>To detect, prevent, and address technical issues</li>
                    <li>To comply with legal obligations</li>
                    <li>To protect our rights and interests</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>4. How We Share Your Information</h2>
                <p>We may share your information with the following parties:</p>
                
                <h3>4.1 Service Providers</h3>
                <p>We may share your information with third-party service providers who perform services on our behalf, such as:</p>
                <ul>
                    <li>Payment processing</li>
                    <li>Course delivery and hosting</li>
                    <li>Email communication</li>
                    <li>Customer support</li>
                    <li>Analytics and data processing</li>
                    <li>Marketing and advertising</li>
                </ul>
                <p>These service providers are contractually obligated to protect your information and use it only for the purposes we specify.</p>

                <h3>4.2 Business Partners</h3>
                <p>We may share your information with trusted business partners for joint promotional activities or to offer you additional products or services that may be of interest to you. We will only share information with your consent.</p>

                <h3>4.3 Legal Requirements</h3>
                <p>We may disclose your information if required by law, regulation, or legal process, or to respond to valid requests from law enforcement or government agencies.</p>

                <h3>4.4 Business Transfers</h3>
                <p>In the event of a merger, acquisition, bankruptcy, or sale of all or a portion of our assets, your information may be transferred as part of the transaction. We will notify you of any such change in ownership or control of your information.</p>
            </div>

            <div class="content-section">
                <h2>5. Data Security</h2>
                <p>We take reasonable measures to protect your information from unauthorized access, use, disclosure, alteration, or destruction. These measures include:</p>
                <ul>
                    <li>Encryption of sensitive data</li>
                    <li>Secure socket layer (SSL) technology for data transmission</li>
                    <li>Access controls and authentication mechanisms</li>
                    <li>Regular security audits and vulnerability assessments</li>
                    <li>Employee training on data protection</li>
                </ul>
                <p>However, no method of transmission over the Internet or electronic storage is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.</p>
            </div>

            <div class="content-section">
                <h2>6. Your Rights</h2>
                <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
                <ul>
                    <li>The right to access your personal information</li>
                    <li>The right to correct inaccurate information</li>
                    <li>The right to delete your information</li>
                    <li>The right to restrict or object to processing</li>
                    <li>The right to data portability</li>
                    <li>The right to withdraw consent</li>
                </ul>
                <p>To exercise these rights, please contact us using the information provided in the "Contact Us" section below. We will respond to your request within a reasonable timeframe.</p>
            </div>

            <div class="content-section">
                <h2>7. Children's Privacy</h2>
                <p>Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children under 13. If we become aware that we have collected information from a child under 13, we will take steps to delete that information as soon as possible.</p>
            </div>

            <div class="content-section">
                <h2>8. Cookies and Tracking Technologies</h2>
                <p>We use cookies and similar tracking technologies to enhance your experience on our website. Cookies are small text files stored on your device that help us recognize your browser and remember certain information about your visit.</p>
                <p>For more information about how we use cookies and how you can manage them, please see our <a href="<?= APP_URL ?>/cookies.php">Cookie Policy</a>.</p>
            </div>

            <div class="content-section">
                <h2>9. Third-Party Links</h2>
                <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of these third-party sites. We encourage you to review the privacy policies of any third-party sites you visit.</p>
            </div>

            <div class="content-section">
                <h2>10. Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time. When we do, we will post the revised policy on this page and update the "Last Updated" date. We encourage you to review this policy periodically to stay informed about our information practices.</p>
                <p>Your continued use of our website or services after the posting of changes constitutes your acceptance of the revised policy.</p>
            </div>

            <div class="content-section">
                <h2>11. Contact Us</h2>
                <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our information practices, please contact us at:</p>
                <div class="highlight-box">
                    <p><strong>Data Tutors</strong><br>
                    Email: privacy@datatutors.com<br>
                    Address: 123 Education Street, Learning City, LC 12345</p>
                </div>

                <div class="contact-info">
                    <h3>Need Help?</h3>
                    <p>If you have questions about how we protect your privacy, our support team is here to assist you.</p>
                    <a href="<?= APP_URL ?>/contact.php" class="contact-button">Contact Support</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>