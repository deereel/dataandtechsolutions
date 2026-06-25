<?php
/**
 * Data Tutors - Affiliate Program Page
 * Information about our affiliate program and partnership opportunities
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Affiliate Program');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Join our affiliate program and earn commissions by promoting Data Tutors courses to your audience.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .affiliates-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            text-align: center;
        }

        .affiliates-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .affiliates-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .program-highlights {
            padding: 6rem 0;
            background: white;
        }

        .highlights-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .highlights-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .highlights-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .highlights-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .highlights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .highlight-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            text-align: center;
        }

        .highlight-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .highlight-icon {
            width: 80px;
            height: 80px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        .highlight-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .highlight-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .how-it-works {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .how-it-works-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .how-it-works-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .how-it-works-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .how-it-works-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .steps {
            display: grid;
            gap: 3rem;
        }

        .step {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 2rem;
            align-items: center;
        }

        .step-number {
            width: 80px;
            height: 80px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
        }

        .step-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .step-content p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        .commission-section {
            padding: 6rem 0;
            background: white;
        }

        .commission-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
            text-align: center;
        }

        .commission-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .commission-container p {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 3rem;
        }

        .commission-tiers {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .commission-tier {
            background: var(--gray-50);
            padding: 2rem;
            border-radius: var(--radius-lg);
            border: 2px solid var(--gray-200);
            transition: var(--transition);
        }

        .commission-tier:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
        }

        .commission-tier h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .commission-rate {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .commission-rate span {
            font-size: 1rem;
            color: var(--gray-600);
            font-weight: 400;
        }

        .commission-qualification {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .benefits-section {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .benefits-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .benefits-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .benefits-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .benefits-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .benefit-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            text-align: center;
        }

        .benefit-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .benefit-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
        }

        .benefit-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .benefit-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .join-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }

        .join-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .join-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .join-container p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .join-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: var(--primary);
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1.125rem;
            transition: var(--transition);
        }

        .join-button:hover {
            background: var(--gray-50);
            transform: translateY(-2px);
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

        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .faq-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
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
            .affiliates-hero {
                padding: 4rem 0;
            }

            .affiliates-hero h1 {
                font-size: 2rem;
            }

            .affiliates-hero p {
                font-size: 1rem;
            }

            .hero-stats {
                gap: 2rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .program-highlights,
            .how-it-works,
            .commission-section,
            .benefits-section,
            .join-section,
            .faq-section {
                padding: 4rem 0;
            }

            .highlights-header h2,
            .how-it-works-header h2,
            .commission-container h2,
            .benefits-header h2,
            .join-container h2 {
                font-size: 2rem;
            }

            .highlights-grid,
            .benefits-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .step {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .commission-tiers {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="affiliates-hero">
        <div class="container">
            <h1>Affiliate Program</h1>
            <p>Earn commissions by promoting Data Tutors courses to your audience. Join our growing community of affiliate partners today!</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">30%</div>
                    <div class="stat-label">Commission Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30 Days</div>
                    <div class="stat-label">Cookie Duration</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">$49+</div>
                    <div class="stat-label">Avg. Commission</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Highlights -->
    <section class="program-highlights">
        <div class="highlights-container">
            <div class="highlights-header">
                <h2>Why Join Our Affiliate Program?</h2>
                <p>We offer one of the most competitive affiliate programs in the e-learning industry. Here's what you can expect:</p>
            </div>

            <div class="highlights-grid">
                <div class="highlight-card">
                    <div class="highlight-icon">💰</div>
                    <h3>High Commission Rates</h3>
                    <p>Earn up to 30% commission on every course sale. Our high-ticket courses mean higher earnings per referral.</p>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon">📊</div>
                    <h3>Real-Time Tracking</h3>
                    <p>Comprehensive dashboard to track your referrals, conversions, and earnings in real time.</p>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon">🎯</div>
                    <h3>Quality Courses</h3>
                    <p>Promote high-quality courses with excellent student satisfaction ratings and retention.</p>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon">📈</div>
                    <h3>Growing Audience</h3>
                    <p>Data skills are in high demand. We see consistent growth in student enrollments month over month.</p>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon">🛡️</div>
                    <h3>Trusted Brand</h3>
                    <p>Data Tutors is a trusted name in the industry with thousands of satisfied students and positive reviews.</p>
                </div>

                <div class="highlight-card">
                    <div class="highlight-icon">🤝</div>
                    <h3>Dedicated Support</h3>
                    <p>Our affiliate support team is here to help you succeed with personalized guidance and resources.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="how-it-works-container">
            <div class="how-it-works-header">
                <h2>How It Works</h2>
                <p>Getting started with our affiliate program is easy. Follow these simple steps to begin earning commissions:</p>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Sign Up</h3>
                        <p>Complete our simple affiliate application form. It takes just a few minutes and we review applications within 24-48 hours.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Get Your Links</h3>
                        <p>Once approved, you'll get access to your unique affiliate links, banners, and promotional materials through our affiliate dashboard.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Promote Courses</h3>
                        <p>Share your affiliate links with your audience through your blog, social media, email list, or other channels.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Earn Commissions</h3>
                        <p>When someone clicks your link and purchases a course, you earn a commission. We track all referrals automatically.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h3>Get Paid</h3>
                        <p>We pay commissions on a monthly basis once you reach the minimum payout threshold. Multiple payment options available.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Commission Structure -->
    <section class="commission-section">
        <div class="commission-container">
            <h2>Commission Structure</h2>
            <p>We offer a tiered commission structure that rewards high-performing affiliates with higher commission rates.</p>
            
            <div class="commission-tiers">
                <div class="commission-tier">
                    <h3>Starter</h3>
                    <div class="commission-rate">25%<span>/sale</span></div>
                    <p class="commission-qualification">For all affiliates</p>
                </div>

                <div class="commission-tier">
                    <h3>Pro</h3>
                    <div class="commission-rate">30%<span>/sale</span></div>
                    <p class="commission-qualification">$1,000+ in monthly earnings</p>
                </div>

                <div class="commission-tier">
                    <h3>Elite</h3>
                    <div class="commission-rate">35%<span>/sale</span></div>
                    <p class="commission-qualification">$5,000+ in monthly earnings</p>
                </div>
            </div>

            <p style="color: var(--gray-600); font-size: 0.875rem;">*Commission rates are based on monthly sales volume. Rates are reviewed quarterly and may be adjusted based on performance.</p>
        </div>
    </section>

    <!-- Benefits -->
    <section class="benefits-section">
        <div class="benefits-container">
            <div class="benefits-header">
                <h2>Affiliate Benefits</h2>
                <p>We provide everything you need to succeed as an affiliate partner</p>
            </div>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">📦</div>
                    <h3>Promotional Materials</h3>
                    <p>Banners, email templates, social media posts, and other resources to help you promote our courses.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">📈</div>
                    <h3>Performance Reports</h3>
                    <p>Detailed reports on clicks, conversions, earnings, and customer behavior to optimize your campaigns.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">💬</div>
                    <h3>Affiliate Support</h3>
                    <p>Dedicated affiliate support team available to answer questions and provide guidance.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">🎁</div>
                    <h3>Exclusive Offers</h3>
                    <p>Access to exclusive promotions, discounts, and affiliate-only deals to share with your audience.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">🔄</div>
                    <h3>Regular Payouts</h3>
                    <p>Monthly payments via PayPal, bank transfer, or other payment methods with low minimum thresholds.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">🌍</div>
                    <h3>Global Program</h3>
                    <p>Our program is open to affiliates worldwide. We support multiple currencies and languages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Now -->
    <section class="join-section">
        <div class="join-container">
            <h2>Ready to Get Started?</h2>
            <p>Join hundreds of successful affiliates who are already earning commissions with Data Tutors. Sign up today and start earning!</p>
            <a href="#" class="join-button">Join Our Affiliate Program</a>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-header">
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about our affiliate program</p>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How do I sign up for the affiliate program?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Signing up is easy! Click the "Join Our Affiliate Program" button and complete the application form. We review all applications within 24-48 hours and will notify you once approved.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How much can I earn?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Commissions start at 25% per sale and can go up to 35% based on your monthly sales volume. The average course price is $49, so you can earn $12.25 per course sale at the starter rate, and even more for high-ticket courses.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How are commissions paid?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>We pay commissions on a monthly basis. Payments are processed within 15 days of the end of each month. You can choose from multiple payment methods including PayPal and bank transfer.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How do I track my earnings?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>We provide a comprehensive affiliate dashboard where you can track clicks, conversions, earnings, and other important metrics in real time. You'll get access to this dashboard once your application is approved.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What promotional materials are available?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>We provide a variety of promotional materials including banners, email templates, social media posts, and text links. All materials are professionally designed to maximize conversions.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Is there a minimum payout threshold?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Yes, the minimum payout threshold is $50. Once your earnings reach this amount, we'll process your payment in the next payment cycle.</p>
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

        // Join button handler
        document.querySelector('.join-button').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Opening affiliate application form');
            // In a real application, this would open an application form
        });
    </script>
</body>
</html>