<?php
/**
 * Data Tutors - Pricing Page
 * Display pricing plans and membership options
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Pricing');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Simple, transparent pricing for Data Tutors courses. Pay once, learn forever.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .pricing-section {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .pricing-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .pricing-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .pricing-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .pricing-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 2rem;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .pricing-card.featured {
            border: 2px solid var(--primary);
            transform: scale(1.05);
        }

        .pricing-card.featured:hover {
            transform: translateY(-8px) scale(1.05);
        }

        .pricing-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .pricing-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .pricing-description {
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-size: 0.9375rem;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .pricing-price span {
            font-size: 1rem;
            font-weight: 400;
            color: var(--gray-500);
        }

        .pricing-period {
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 2rem;
        }

        .pricing-features li {
            padding: 0.75rem 0;
            color: var(--gray-700);
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pricing-features li::before {
            content: '✓';
            color: var(--success);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .pricing-button {
            width: 100%;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .pricing-button-primary {
            background: var(--primary);
            color: white;
        }

        .pricing-button-primary:hover {
            background: var(--primary-dark);
        }

        .pricing-button-secondary {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .pricing-button-secondary:hover {
            background: var(--gray-50);
        }

        .pricing-comparison {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 3rem;
        }

        .pricing-comparison h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }

        .comparison-table thead {
            background: var(--gray-50);
        }

        .comparison-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
        }

        .comparison-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        .comparison-table .check {
            color: var(--success);
            font-weight: 700;
        }

        .comparison-table .cross {
            color: var(--gray-400);
        }

        .faq-section {
            background: white;
            padding: 4rem 0;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .faq-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .faq-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
        }

        .faq-item {
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .faq-question {
            padding: 1.25rem 1.5rem;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .faq-question:hover {
            background: var(--gray-50);
        }

        .faq-question::after {
            content: '+';
            font-size: 1.5rem;
            color: var(--gray-400);
            transition: transform 0.3s ease;
        }

        .faq-question.active::after {
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer.active {
            max-height: 500px;
        }

        .faq-answer-content {
            padding: 0 1.5rem 1.25rem;
            color: var(--gray-600);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .pricing-section {
                padding: 4rem 0;
            }

            .pricing-header h1 {
                font-size: 2rem;
            }

            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .pricing-card.featured {
                transform: scale(1);
            }

            .pricing-card.featured:hover {
                transform: translateY(-8px);
            }

            .comparison-table {
                font-size: 0.875rem;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Pricing Section -->
    <section class="pricing-section">
        <div class="pricing-container">
            <div class="pricing-header">
                <h1>Simple, Transparent Pricing</h1>
                <p>Pay once, learn forever. No hidden fees or subscription traps.</p>
            </div>

            <div class="pricing-grid">
                <!-- Single Course Plan -->
                <div class="pricing-card">
                    <div class="pricing-title">Single Course</div>
                    <div class="pricing-description">Perfect for focused learning</div>
                    <div class="pricing-price">$49<span>/course</span></div>
                    <div class="pricing-period">Lifetime access</div>
                    <ul class="pricing-features">
                        <li>Access to one course</li>
                        <li>All course materials</li>
                        <li>Quizzes & assessments</li>
                        <li>Community forum access</li>
                        <li>Course certificate</li>
                        <li>Downloadable resources</li>
                        <li>Free updates</li>
                        <li>30-day money-back guarantee</li>
                    </ul>
                    <a href="<?= APP_URL ?>/course/index.php" class="pricing-button pricing-button-secondary">
                        Browse Courses
                    </a>
                </div>

                <!-- All Access Plan -->
                <div class="pricing-card featured">
                    <span class="pricing-badge">Most Popular</span>
                    <div class="pricing-title">All Access</div>
                    <div class="pricing-description">Everything you need to master data skills</div>
                    <div class="pricing-price">$149<span>/year</span></div>
                    <div class="pricing-period">Unlimited access</div>
                    <ul class="pricing-features">
                        <li>Access to all courses</li>
                        <li>All course materials</li>
                        <li>Quizzes & assessments</li>
                        <li>Priority forum support</li>
                        <li>All certificates</li>
                        <li>Downloadable resources</li>
                        <li>New courses included</li>
                        <li>Priority updates</li>
                        <li>30-day money-back guarantee</li>
                    </ul>
                    <a href="<?= APP_URL ?>/auth/register.php" class="pricing-button pricing-button-primary">
                        Get Started
                    </a>
                </div>

                <!-- Team Plan -->
                <div class="pricing-card">
                    <div class="pricing-title">Team</div>
                    <div class="pricing-description">For teams and organizations</div>
                    <div class="pricing-price">$499<span>/year</span></div>
                    <div class="pricing-period">Up to 10 team members</div>
                    <ul class="pricing-features">
                        <li>Up to 10 team members</li>
                        <li>All course access</li>
                        <li>Team progress tracking</li>
                        <li>Dedicated support</li>
                        <li>Custom certificates</li>
                        <li>Invoice billing</li>
                        <li>Team management dashboard</li>
                        <li>Training roadmap consulting</li>
                        <li>Custom content options</li>
                    </ul>
                    <a href="<?= APP_URL ?>/contact.php" class="pricing-button pricing-button-secondary">
                        Contact Sales
                    </a>
                </div>
            </div>

            <!-- Pricing Comparison -->
            <div class="pricing-comparison">
                <h2>Plan Comparison</h2>
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Single Course</th>
                            <th>All Access</th>
                            <th>Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Course Access</td>
                            <td><span class="check">✓</span> 1 Course</td>
                            <td><span class="check">✓</span> All Courses</td>
                            <td><span class="check">✓</span> All Courses</td>
                        </tr>
                        <tr>
                            <td>Certificates</td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                        </tr>
                        <tr>
                            <td>Downloadable Resources</td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                        </tr>
                        <tr>
                            <td>Forum Support</td>
                            <td><span class="check">✓</span> Community</td>
                            <td><span class="check">✓</span> Priority</td>
                            <td><span class="check">✓</span> Dedicated</td>
                        </tr>
                        <tr>
                            <td>Course Updates</td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                            <td><span class="check">✓</span></td>
                        </tr>
                        <tr>
                            <td>Progress Tracking</td>
                            <td><span class="check">✓</span> Individual</td>
                            <td><span class="check">✓</span> Individual</td>
                            <td><span class="check">✓</span> Team</td>
                        </tr>
                        <tr>
                            <td>Team Management</td>
                            <td><span class="cross">✗</span></td>
                            <td><span class="cross">✗</span></td>
                            <td><span class="check">✓</span></td>
                        </tr>
                        <tr>
                            <td>Custom Content</td>
                            <td><span class="cross">✗</span></td>
                            <td><span class="cross">✗</span></td>
                            <td><span class="check">✓</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-header">
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about our pricing and courses</p>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Do I get lifetime access to courses?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Yes! When you purchase any course or membership, you get lifetime access to all course materials, updates, and future content additions. Your purchase includes all current and future lessons, quizzes, and resources.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Is there a money-back guarantee?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Absolutely. We offer a 30-day money-back guarantee. If you're not satisfied with your purchase for any reason, simply contact us within 30 days for a full refund. No questions asked.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I upgrade my plan later?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Yes, you can upgrade from a single course to the All Access or Team plan at any time. We'll credit your existing purchase towards the new plan. Contact our support team for assistance with upgrades.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What payment methods do you accept?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        We accept all major credit cards, PayPal, and bank transfers. For team plans, we also offer invoice billing and purchase orders. All transactions are secure and encrypted.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Do you offer corporate training solutions?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        Yes! We offer custom corporate training solutions with dedicated support, team management features, and custom content options. Contact our sales team to discuss your organization's specific needs.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // FAQ toggle functionality
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('active');
                const answer = button.nextElementSibling;
                answer.classList.toggle('active');
            });
        });
    </script>
</body>
</html>