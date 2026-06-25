<?php
/**
 * Data Tutors - About Us Page
 * Tell the story of Data Tutors and our mission
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'About Us');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Learn about Data Tutors - our mission, team, and why we're passionate about helping people master data skills.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .about-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }

        .about-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .about-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .mission-section {
            padding: 6rem 0;
            background: white;
        }

        .mission-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .mission-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .mission-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--gray-400);
        }

        .mission-text h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }

        .mission-text p {
            font-size: 1.125rem;
            line-height: 1.8;
            color: var(--gray-600);
            margin-bottom: 1.5rem;
        }

        .features-section {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .features-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .feature-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .team-section {
            padding: 6rem 0;
            background: white;
        }

        .team-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .team-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .team-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .team-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .team-member {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .team-member:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }

        .team-member h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
        }

        .team-member .role {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 1rem;
            font-size: 0.9375rem;
        }

        .team-member .bio {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }

        .team-social {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .team-social a {
            width: 40px;
            height: 40px;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: var(--transition);
        }

        .team-social a:hover {
            background: var(--primary);
            color: white;
        }

        .stats-section {
            padding: 6rem 0;
            background: var(--primary);
            color: white;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .cta-section {
            padding: 6rem 0;
            background: var(--gray-900);
            color: white;
            text-align: center;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .cta-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
        }

        .cta-container p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .cta-button {
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

        .cta-button:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .about-hero {
                padding: 4rem 0;
            }

            .about-hero h1 {
                font-size: 2rem;
            }

            .about-hero p {
                font-size: 1rem;
            }

            .mission-section {
                padding: 4rem 0;
            }

            .mission-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .mission-image {
                height: 250px;
                font-size: 2.5rem;
            }

            .mission-text h2 {
                font-size: 1.5rem;
            }

            .features-section,
            .team-section,
            .stats-section,
            .cta-section {
                padding: 4rem 0;
            }

            .features-header h2,
            .team-header h2,
            .cta-container h2 {
                font-size: 2rem;
            }

            .stats-grid {
                gap: 2rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>About Data Tutors</h1>
            <p>Empowering people with data skills to advance their careers and transform their lives.</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="mission-container">
            <div class="mission-content">
                <div class="mission-image">
                    📊
                </div>
                <div class="mission-text">
                    <h2>Our Mission</h2>
                    <p>At Data Tutors, we believe that data literacy is the most important skill for the 21st century. Our mission is to make high-quality data education accessible to everyone, regardless of their background or experience level.</p>
                    <p>We're passionate about helping people master essential data skills like Excel, SQL, Python, Power BI, and data visualization through practical, hands-on courses taught by industry experts.</p>
                    <p>Whether you're a complete beginner or looking to advance your career, we provide the tools and resources you need to succeed in today's data-driven world.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-container">
            <div class="features-header">
                <h2>Why Choose Data Tutors?</h2>
                <p>Everything you need to master data skills and advance your career</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3>Expert Instructors</h3>
                    <p>Learn from industry professionals with real-world experience and proven track records in data analytics and automation.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Practical Learning</h3>
                    <p>Hands-on exercises, real projects, and case studies that prepare you for real-world challenges in data analysis.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>Learn at Your Pace</h3>
                    <p>Flexible learning with lifetime access to courses, allowing you to learn whenever and wherever you want.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📈</div>
                    <h3>Career Focused</h3>
                    <p>Course content designed to help you advance your career, prepare for interviews, and build a strong portfolio.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Affordable Pricing</h3>
                    <p>High-quality education at accessible prices with no hidden fees or subscription traps.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Supportive Community</h3>
                    <p>Join thousands of learners in our active community forum for support, networking, and knowledge sharing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="team-container">
            <div class="team-header">
                <h2>Meet Our Team</h2>
                <p>Passionate educators and industry experts dedicated to your success</p>
            </div>

            <div class="team-grid">
                <div class="team-member">
                    <div class="team-avatar">MD</div>
                    <h3>Michael Davis</h3>
                    <div class="role">Founder & Lead Instructor</div>
                    <div class="bio">Data scientist with 10+ years of experience in analytics and machine learning. Former senior data analyst at leading tech companies.</div>
                    <div class="team-social">
                        <a href="#" aria-label="LinkedIn">in</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="GitHub">🐙</a>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-avatar">SJ</div>
                    <h3>Sarah Johnson</h3>
                    <div class="role">Excel & Power BI Expert</div>
                    <div class="bio">Excel MVP and Power BI specialist with expertise in financial modeling and business intelligence solutions.</div>
                    <div class="team-social">
                        <a href="#" aria-label="LinkedIn">in</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="GitHub">🐙</a>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-avatar">RK</div>
                    <h3>Rajesh Kumar</h3>
                    <div class="role">SQL & Database Specialist</div>
                    <div class="bio">Database architect and SQL expert with 15+ years of experience in data management and performance optimization.</div>
                    <div class="team-social">
                        <a href="#" aria-label="LinkedIn">in</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="GitHub">🐙</a>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-avatar">LM</div>
                    <h3>Laura Martinez</h3>
                    <div class="role">Data Visualization Expert</div>
                    <div class="bio">Tableau and Power BI specialist focused on creating compelling data visualizations that drive business insights.</div>
                    <div class="team-social">
                        <a href="#" aria-label="LinkedIn">in</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="GitHub">🐙</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Students</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Courses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Certificate Programs</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Start Your Data Journey Today</h2>
            <p>Join thousands of learners who have already transformed their careers with Data Tutors. Start with our free courses or choose a premium plan that fits your needs.</p>
            <a href="<?= APP_URL ?>/auth/register.php" class="cta-button">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>