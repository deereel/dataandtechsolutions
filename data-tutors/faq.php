<?php
/**
 * Data Tutors - FAQ Page
 * Frequently asked questions about courses, pricing, and learning
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'FAQ');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Frequently asked questions about Data Tutors courses, pricing, learning platform, and support.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .faq-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .faq-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .faq-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-section {
            padding: 6rem 0;
            background: white;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .faq-categories {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 4rem;
            flex-wrap: wrap;
        }

        .category-tab {
            padding: 0.75rem 1.5rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9375rem;
        }

        .category-tab:hover {
            background: var(--gray-200);
        }

        .category-tab.active {
            background: var(--primary);
            color: white;
        }

        .faq-items {
            display: grid;
            gap: 1rem;
        }

        .faq-item {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item:hover {
            box-shadow: var(--shadow);
        }

        .faq-question {
            width: 100%;
            padding: 1.5rem;
            background: white;
            border: none;
            text-align: left;
            font-size: 1.125rem;
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
            max-height: 1000px;
        }

        .faq-answer-content {
            padding: 0 1.5rem 1.5rem;
            color: var(--gray-600);
            line-height: 1.8;
            font-size: 1rem;
        }

        .faq-answer-content p {
            margin-bottom: 1rem;
        }

        .faq-answer-content ul {
            list-style: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .faq-answer-content li {
            margin-bottom: 0.5rem;
        }

        .contact-section {
            padding: 6rem 0;
            background: var(--gray-50);
            text-align: center;
        }

        .contact-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .contact-container h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .contact-container p {
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-size: 1.125rem;
        }

        .contact-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1.125rem;
            transition: var(--transition);
        }

        .contact-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .faq-hero {
                padding: 4rem 0;
            }

            .faq-hero h1 {
                font-size: 2rem;
            }

            .faq-hero p {
                font-size: 1rem;
            }

            .faq-section,
            .contact-section {
                padding: 4rem 0;
            }

            .faq-categories {
                flex-direction: column;
                gap: 0.5rem;
            }

            .category-tab {
                width: 100%;
                text-align: center;
            }

            .faq-question {
                font-size: 1rem;
                padding: 1rem;
            }

            .faq-answer-content {
                padding: 0 1rem 1rem;
                font-size: 0.9375rem;
            }

            .contact-container h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="faq-hero">
        <div class="container">
            <h1>Frequently Asked Questions</h1>
            <p>Everything you need to know about Data Tutors courses, pricing, and learning platform.</p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-categories">
                <button class="category-tab active" data-category="all">All Questions</button>
                <button class="category-tab" data-category="courses">Courses</button>
                <button class="category-tab" data-category="pricing">Pricing</button>
                <button class="category-tab" data-category="learning">Learning</button>
                <button class="category-tab" data-category="support">Support</button>
            </div>

            <div class="faq-items">
                <!-- Courses Questions -->
                <div class="faq-item" data-category="courses">
                    <button class="faq-question">
                        What courses do you offer?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>We offer a comprehensive range of courses designed to help you master data skills, including:</p>
                            <ul>
                                <li>Excel Mastery (from beginner to advanced)</li>
                                <li>SQL for Data Analysis</li>
                                <li>Power BI & Data Visualization</li>
                                <li>Python for Data Analysis</li>
                                <li>Data Analysis Fundamentals</li>
                                <li>Data Automation with Zapier and Make.com</li>
                            </ul>
                            <p>All courses include video lessons, hands-on exercises, quizzes, and downloadable resources.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="courses">
                    <button class="faq-question">
                        How long does it take to complete a course?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Course duration varies depending on the subject and your learning pace. On average:</p>
                            <ul>
                                <li>Beginner courses: 10-15 hours</li>
                                <li>Intermediate courses: 20-30 hours</li>
                                <li>Advanced courses: 40+ hours</li>
                            </ul>
                            <p>You have lifetime access to all course materials, so you can learn at your own pace and revisit content whenever you need.</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Questions -->
                <div class="faq-item" data-category="pricing">
                    <button class="faq-question">
                        What payment methods do you accept?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>We accept all major credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers. For team plans, we also offer invoice billing and purchase orders.</p>
                            <p>All transactions are secure and encrypted using industry-standard SSL technology.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="pricing">
                    <button class="faq-question">
                        Is there a money-back guarantee?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Yes! We offer a 30-day money-back guarantee. If you're not satisfied with your purchase for any reason, simply contact us within 30 days for a full refund. No questions asked.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="pricing">
                    <button class="faq-question">
                        Can I upgrade my plan later?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Absolutely! You can upgrade from a single course to the All Access or Team plan at any time. We'll credit your existing purchase towards the new plan. Contact our support team for assistance with upgrades.</p>
                        </div>
                    </div>
                </div>

                <!-- Learning Questions -->
                <div class="faq-item" data-category="learning">
                    <button class="faq-question">
                        Do I need any prior experience?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Most of our courses are designed for beginners with no prior experience. We offer courses for all skill levels, from absolute beginners to advanced data professionals.</p>
                            <p>Each course includes prerequisites information, and our beginner courses start with the fundamentals.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="learning">
                    <button class="faq-question">
                        Can I download course materials?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Yes! Most of our courses include downloadable resources such as exercise files, templates, cheat sheets, and reference materials. You can download these files to your computer for offline use.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="learning">
                    <button class="faq-question">
                        Will I receive a certificate?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>Yes! Upon successfully completing a course, you'll receive a verifiable certificate of completion that you can download and share on LinkedIn or include in your professional portfolio.</p>
                            <p>Certificates are automatically awarded when you complete all course requirements, including passing the final assessment with a minimum score of 70%.</p>
                        </div>
                    </div>
                </div>

                <!-- Support Questions -->
                <div class="faq-item" data-category="support">
                    <button class="faq-question">
                        How do I get help if I'm stuck?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>We offer several ways to get support:</p>
                            <ul>
                                <li><strong>Community Forum:</strong> Join our active community of learners and instructors for discussions and Q&A</li>
                                <li><strong>Instructor Support:</strong> Ask questions directly to course instructors</li>
                                <li><strong>Email Support:</strong> Contact our support team for technical or course-related issues</li>
                                <li><strong>Live Q&A Sessions:</strong> Participate in monthly live Q&A sessions with instructors</li>
                            </ul>
                            <p>Response times vary depending on the support channel, but we aim to address all inquiries within 24-48 hours.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="support">
                    <button class="faq-question">
                        What if I forget my password?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>You can reset your password by clicking on the "Forgot Password" link on the login page. We'll send you an email with instructions to reset your password.</p>
                            <p>If you don't receive the email, please check your spam folder or contact our support team for assistance.</p>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="support">
                    <button class="faq-question">
                        How do I report technical issues?
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>If you encounter any technical issues with the platform or course materials, please contact our support team with the following information:</p>
                            <ul>
                                <li>Your account email</li>
                                <li>Course name and lesson title</li>
                                <li>Detailed description of the issue</li>
                                <li>Browser and device information</li>
                                <li>Screenshots or error messages (if applicable)</li>
                            </ul>
                            <p>Our support team will investigate and resolve the issue as quickly as possible.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <h2>Still Have Questions?</h2>
            <p>Can't find the answer to your question? Our support team is here to help.</p>
            <a href="<?= APP_URL ?>/contact.php" class="contact-button">Contact Support</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // FAQ category filtering
        const categoryTabs = document.querySelectorAll('.category-tab');
        const faqItems = document.querySelectorAll('.faq-item');

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                categoryTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                tab.classList.add('active');
                
                const category = tab.dataset.category;
                
                // Show/hide FAQ items based on category
                faqItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // FAQ question toggle
        const faqQuestions = document.querySelectorAll('.faq-question');

        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                question.classList.toggle('active');
                const answer = question.nextElementSibling;
                answer.classList.toggle('active');
            });
        });
    </script>
</body>
</html>