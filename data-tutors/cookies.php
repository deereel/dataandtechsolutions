<?php
/**
 * Data Tutors - Cookie Policy
 * Information about how we use cookies and tracking technologies
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Cookie Policy');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Data Tutors Cookie Policy - Information about how we use cookies and tracking technologies.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .cookies-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .cookies-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .cookies-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .cookies-content {
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

        .cookie-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border: 1px solid var(--gray-200);
        }

        .cookie-table th,
        .cookie-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        .cookie-table th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-900);
        }

        .cookie-table tr:last-child td {
            border-bottom: none;
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
            .cookies-hero {
                padding: 4rem 0;
            }

            .cookies-hero h1 {
                font-size: 2rem;
            }

            .cookies-hero p {
                font-size: 1rem;
            }

            .cookies-content {
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

            .cookie-table {
                font-size: 0.875rem;
            }

            .cookie-table th,
            .cookie-table td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="cookies-hero">
        <div class="container">
            <h1>Cookie Policy</h1>
            <p>Information about how we use cookies and tracking technologies on our website.</p>
        </div>
    </section>

    <!-- Cookie Content -->
    <section class="cookies-content">
        <div class="content-container">
            <div class="last-updated">
                <p><strong>Last Updated:</strong> February 21, 2026</p>
            </div>

            <div class="content-section">
                <h2>1. What Are Cookies?</h2>
                <p>Cookies are small text files that are stored on your device when you visit our website. They help us recognize your browser and remember certain information about your visit.</p>
                <p>We use cookies and similar tracking technologies to enhance your experience, analyze usage, and improve our services. This Cookie Policy explains how we use cookies and how you can manage them.</p>
            </div>

            <div class="content-section">
                <h2>2. Types of Cookies We Use</h2>
                
                <h3>2.1 Essential Cookies</h3>
                <p>These cookies are necessary for the website to function properly. They enable basic functions like page navigation, access to secure areas, and form submission.</p>
                <p>Examples:</p>
                <ul>
                    <li>Session cookies - maintain your session state</li>
                    <li>Authentication cookies - keep you logged in</li>
                    <li>Security cookies - protect against fraud</li>
                </ul>

                <h3>2.2 Analytical/Performance Cookies</h3>
                <p>These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously.</p>
                <p>Examples:</p>
                <ul>
                    <li>Google Analytics - track website traffic and user behavior</li>
                    <li>Heatmaps - show where users click and scroll</li>
                    <li>Session recording - record user sessions for analysis</li>
                </ul>

                <h3>2.3 Functionality Cookies</h3>
                <p>These cookies enable enhanced functionality and personalization. They may be set by us or by third-party providers.</p>
                <p>Examples:</p>
                <ul>
                    <li>Preference cookies - remember your settings</li>
                    <li>Language preference - store your language choice</li>
                    <li>Customization - remember your course preferences</li>
                </ul>

                <h3>2.4 Targeting/Advertising Cookies</h3>
                <p>These cookies are used to deliver relevant advertisements and marketing campaigns. They track your browsing habits to show you ads that may be of interest to you.</p>
                <p>Examples:</p>
                <ul>
                    <li>Google Ads - show targeted ads</li>
                    <li>Facebook Pixel - track conversions from ads</li>
                    <li>Retargeting cookies - show ads based on your activity</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>3. How We Use Cookies</h2>
                <p>We use cookies for the following purposes:</p>
                <ul>
                    <li>To enable basic functionality of our website</li>
                    <li>To authenticate users and maintain session state</li>
                    <li>To personalize your learning experience</li>
                    <li>To analyze website usage and improve performance</li>
                    <li>To deliver targeted advertising</li>
                    <li>To understand how visitors interact with our content</li>
                    <li>To remember your preferences and settings</li>
                </ul>

                <h3>3.1 Third-Party Cookies</h3>
                <p>We may allow third-party service providers to set cookies on our website. These include:</p>
                <div class="cookie-table">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Purpose</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Google Analytics</td>
                            <td>Analytics and user behavior tracking</td>
                            <td>Analytical</td>
                        </tr>
                        <tr>
                            <td>Google Ads</td>
                            <td>Targeted advertising and remarketing</td>
                            <td>Advertising</td>
                        </tr>
                        <tr>
                            <td>Facebook Pixel</td>
                            <td>Conversion tracking and advertising</td>
                            <td>Advertising</td>
                        </tr>
                        <tr>
                            <td>Hotjar</td>
                            <td>User experience and behavior analysis</td>
                            <td>Analytical</td>
                        </tr>
                    </tbody>
                </div>
            </div>

            <div class="content-section">
                <h2>4. Managing Cookies</h2>
                
                <h3>4.1 Browser Settings</h3>
                <p>You can manage cookies through your browser settings. Most browsers allow you to:</p>
                <ul>
                    <li>View cookies stored on your device</li>
                    <li>Delete cookies</li>
                    <li>Block all or specific cookies</li>
                    <li>Receive a notification when cookies are set</li>
                </ul>
                <p>Please note that disabling cookies may affect the functionality of our website. Some features may not work properly if cookies are disabled.</p>

                <h3>4.2 Cookie Preference Center</h3>
                <p>You can also manage your cookie preferences through our cookie consent banner. You can choose to:</p>
                <ul>
                    <li>Accept all cookies</li>
                    <li>Reject non-essential cookies</li>
                    <li>Customize your cookie settings</li>
                </ul>

                <h3>4.3 Do Not Track</h3>
                <p>Some browsers offer a "Do Not Track" (DNT) feature. Currently, we do not respond to DNT signals. However, you can manage your cookie settings through your browser or our cookie preference center.</p>
            </div>

            <div class="content-section">
                <h2>5. Cookie Retention</h2>
                <p>Cookies have different expiration dates:</p>
                <ul>
                    <li><strong>Session cookies:</strong> Expire when you close your browser</li>
                    <li><strong>Persistent cookies:</strong> Expire after a specific period (ranging from minutes to years)</li>
                    <li><strong>Third-party cookies:</strong> Expiration dates vary by provider</li>
                </ul>
                <p>We typically retain analytical cookies for 1-2 years, while essential cookies may be stored for the duration of your session.</p>
            </div>

            <div class="content-section">
                <h2>6. Changes to This Cookie Policy</h2>
                <p>We may update this Cookie Policy from time to time. When we do, we will post the revised policy on this page and update the "Last Updated" date. We encourage you to review this policy periodically to stay informed about our cookie practices.</p>
                <p>Your continued use of our website after the posting of changes constitutes your acceptance of the revised policy.</p>
            </div>

            <div class="content-section">
                <h2>7. Contact Us</h2>
                <p>If you have any questions, concerns, or requests regarding this Cookie Policy or our cookie practices, please contact us at:</p>
                <div class="highlight-box">
                    <p><strong>Data Tutors</strong><br>
                    Email: privacy@datatutors.com<br>
                    Address: 123 Education Street, Learning City, LC 12345</p>
                </div>

                <div class="contact-info">
                    <h3>Need Help?</h3>
                    <p>If you have questions about how we use cookies, our support team is here to assist you.</p>
                    <a href="<?= APP_URL ?>/contact.php" class="contact-button">Contact Support</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>