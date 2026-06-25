<?php
/**
 * Data Tutors - Careers Page
 * Career opportunities and job openings at Data Tutors
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Careers');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Join Data Tutors! Explore career opportunities, job openings, and join our mission to democratize data education.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .careers-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }

        .careers-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .careers-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .values-section {
            padding: 6rem 0;
            background: white;
        }

        .values-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .values-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .values-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .values-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .value-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            text-align: center;
        }

        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .value-icon {
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

        .value-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .value-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .openings-section {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .openings-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .openings-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .openings-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .openings-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .openings-list {
            display: grid;
            gap: 1.5rem;
        }

        .opening-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .opening-card:hover {
            transform: translateX(8px);
            box-shadow: var(--shadow-lg);
        }

        .opening-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .opening-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .opening-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            color: var(--gray-600);
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }

        .opening-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .opening-description {
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .opening-requirements {
            margin-bottom: 2rem;
        }

        .opening-requirements h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .opening-requirements ul {
            list-style: none;
            padding: 0;
        }

        .opening-requirements li {
            padding: 0.5rem 0;
            color: var(--gray-600);
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .opening-requirements li::before {
            content: '✓';
            color: var(--success);
            font-weight: 700;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .opening-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: var(--radius);
            font-weight: 600;
            transition: var(--transition);
        }

        .opening-button:hover {
            background: var(--primary-dark);
        }

        .benefits-section {
            padding: 6rem 0;
            background: white;
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

        .culture-section {
            padding: 6rem 0;
            background: var(--gray-900);
            color: white;
        }

        .culture-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
            text-align: center;
        }

        .culture-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
        }

        .culture-container p {
            font-size: 1.125rem;
            opacity: 0.9;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .culture-quote {
            font-size: 1.5rem;
            font-style: italic;
            margin: 2rem 0;
            opacity: 0.9;
            position: relative;
            padding: 2rem;
        }

        .culture-quote::before {
            content: '"';
            font-size: 4rem;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0.2;
        }

        .culture-quote::after {
            content: '"';
            font-size: 4rem;
            position: absolute;
            bottom: -2rem;
            right: 0;
            opacity: 0.2;
        }

        .apply-section {
            padding: 6rem 0;
            background: var(--primary);
            color: white;
            text-align: center;
        }

        .apply-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .apply-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .apply-container p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .apply-button {
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

        .apply-button:hover {
            background: var(--gray-50);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .careers-hero {
                padding: 4rem 0;
            }

            .careers-hero h1 {
                font-size: 2rem;
            }

            .careers-hero p {
                font-size: 1rem;
            }

            .values-section,
            .openings-section,
            .benefits-section,
            .culture-section,
            .apply-section {
                padding: 4rem 0;
            }

            .values-header h2,
            .openings-header h2,
            .benefits-header h2,
            .culture-container h2,
            .apply-container h2 {
                font-size: 2rem;
            }

            .values-grid,
            .benefits-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .opening-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .opening-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .culture-quote {
                font-size: 1.25rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="careers-hero">
        <div class="container">
            <h1>Join Our Team</h1>
            <p>Help us democratize data education and empower millions of people with essential data skills.</p>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="values-container">
            <div class="values-header">
                <h2>Our Values</h2>
                <p>We're a team of passionate educators, developers, and designers working together to make data education accessible to everyone.</p>
            </div>

            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">🎓</div>
                    <h3>Education First</h3>
                    <p>We believe in the transformative power of education and are committed to providing high-quality learning experiences.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🚀</div>
                    <h3>Innovation</h3>
                    <p>We continuously innovate to create better learning tools and methodologies that adapt to the changing needs of learners.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🤝</div>
                    <h3>Community</h3>
                    <p>We foster a supportive community where learners and educators can connect, share knowledge, and grow together.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">💪</div>
                    <h3>Excellence</h3>
                    <p>We strive for excellence in everything we do, from course content to platform design to customer support.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🎯</div>
                    <h3>Impact</h3>
                    <p>We measure our success by the impact we have on learners' lives and careers through the skills they acquire.</p>
                </div>

                <div class="value-card">
                    <div class="value-icon">🌍</div>
                    <h3>Accessibility</h3>
                    <p>We're committed to making high-quality data education accessible to people from all backgrounds and socioeconomic levels.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Open Positions -->
    <section class="openings-section">
        <div class="openings-container">
            <div class="openings-header">
                <h2>Open Positions</h2>
                <p>We're always looking for talented individuals who share our passion for education and innovation.</p>
            </div>

            <div class="openings-list">
                <!-- Job Opening 1 -->
                <div class="opening-card">
                    <div class="opening-header">
                        <h3 class="opening-title">Senior Data Science Instructor</h3>
                        <span class="opening-type">Full-time</span>
                    </div>
                    <div class="opening-meta">
                        <span class="opening-meta-item">
                            <span>🏢</span> Remote (Global)
                        </span>
                        <span class="opening-meta-item">
                            <span>💰</span> $80,000 - $120,000/year
                        </span>
                    </div>
                    <div class="opening-description">
                        <p>We're seeking an experienced data science instructor to create and teach courses on Python, machine learning, and deep learning. You'll work with our curriculum team to develop engaging, practical content that helps learners build real-world skills.</p>
                    </div>
                    <div class="opening-requirements">
                        <h4>Requirements</h4>
                        <ul>
                            <li>5+ years of experience in data science or machine learning</li>
                            <li>Proven teaching or training experience</li>
                            <li>Strong knowledge of Python, Pandas, Scikit-learn, TensorFlow/PyTorch</li>
                            <li>Excellent communication and presentation skills</li>
                            <li>Experience with curriculum development</li>
                        </ul>
                    </div>
                    <a href="#" class="opening-button">Apply Now →</a>
                </div>

                <!-- Job Opening 2 -->
                <div class="opening-card">
                    <div class="opening-header">
                        <h3 class="opening-title">Course Development Specialist</h3>
                        <span class="opening-type">Full-time</span>
                    </div>
                    <div class="opening-meta">
                        <span class="opening-meta-item">
                            <span>🏢</span> Remote (Global)
                        </span>
                        <span class="opening-meta-item">
                            <span>💰</span> $60,000 - $85,000/year
                        </span>
                    </div>
                    <div class="opening-description">
                        <p>We're looking for a course development specialist to help design and create engaging learning experiences. You'll work with instructors and subject matter experts to transform complex technical content into accessible, practical lessons.</p>
                    </div>
                    <div class="opening-requirements">
                        <h4>Requirements</h4>
                        <ul>
                            <li>3+ years of experience in curriculum or course development</li>
                            <li>Understanding of adult learning principles</li>
                            <li>Excellent writing and editing skills</li>
                            <li>Experience with educational technology platforms</li>
                            <li>Knowledge of data analytics or related fields is a plus</li>
                        </ul>
                    </div>
                    <a href="#" class="opening-button">Apply Now →</a>
                </div>

                <!-- Job Opening 3 -->
                <div class="opening-card">
                    <div class="opening-header">
                        <h3 class="opening-title">Web Developer (Full Stack)</h3>
                        <span class="opening-type">Full-time</span>
                    </div>
                    <div class="opening-meta">
                        <span class="opening-meta-item">
                            <span>🏢</span> Remote (Global)
                        </span>
                        <span class="opening-meta-item">
                            <span>💰</span> $70,000 - $100,000/year
                        </span>
                    </div>
                    <div class="opening-description">
                        <p>We're seeking a talented full-stack developer to help build and maintain our learning platform. You'll work with our product team to create intuitive, scalable features that enhance the learning experience.</p>
                    </div>
                    <div class="opening-requirements">
                        <h4>Requirements</h4>
                        <ul>
                            <li>3+ years of full-stack development experience</li>
                            <li>Proficiency in PHP, JavaScript, HTML, CSS</li>
                            <li>Experience with MySQL, PostgreSQL, or similar databases</li>
                            <li>Knowledge of web frameworks and best practices</li>
                            <li>Experience with e-learning platforms is a plus</li>
                        </ul>
                    </div>
                    <a href="#" class="opening-button">Apply Now →</a>
                </div>

                <!-- Job Opening 4 -->
                <div class="opening-card">
                    <div class="opening-header">
                        <h3 class="opening-title">Customer Success Manager</h3>
                        <span class="opening-type">Full-time</span>
                    </div>
                    <div class="opening-meta">
                        <span class="opening-meta-item">
                            <span>🏢</span> Remote (Global)
                        </span>
                        <span class="opening-meta-item">
                            <span>💰</span> $50,000 - $75,000/year
                        </span>
                    </div>
                    <div class="opening-description">
                        <p>We're looking for a customer success manager to help our learners succeed. You'll work directly with learners to understand their needs, provide support, and help them maximize the value of our courses.</p>
                    </div>
                    <div class="opening-requirements">
                        <h4>Requirements</h4>
                        <ul>
                            <li>2+ years of customer success or support experience</li>
                            <li>Excellent communication and problem-solving skills</li>
                            <li>Empathetic and customer-focused mindset</li>
                            <li>Experience with CRM systems</li>
                            <li>Knowledge of data analytics concepts is a plus</li>
                        </ul>
                    </div>
                    <a href="#" class="opening-button">Apply Now →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="benefits-section">
        <div class="benefits-container">
            <div class="benefits-header">
                <h2>Why Join Data Tutors?</h2>
                <p>We offer competitive compensation and comprehensive benefits to support your growth and well-being.</p>
            </div>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">💻</div>
                    <h3>Remote Work</h3>
                    <p>Work from anywhere in the world with our fully remote team.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">📚</div>
                    <h3>Learning Budget</h3>
                    <p>$2,000 annual learning budget for courses, books, conferences, and certifications.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">💰</div>
                    <h3>Competitive Salary</h3>
                    <p>Market-competitive salaries with annual performance reviews and bonuses.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">🏖️</div>
                    <h3>Flexible Time</h3>
                    <p>Choose your work hours and take time off when you need it with our flexible time off policy.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">📈</div>
                    <h3>Career Growth</h3>
                    <p>Opportunities for professional development and career advancement within the company.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">🌱</div>
                    <h3>Impact</h3>
                    <p>Work on meaningful projects that have a real impact on people's lives and careers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Culture -->
    <section class="culture-section">
        <div class="culture-container">
            <h2>Our Culture</h2>
            <p>We're building a company where people can do their best work, learn continuously, and make a meaningful impact.</p>
            <div class="culture-quote">
                "At Data Tutors, we believe that great teams are built on trust, respect, and a shared sense of purpose. We celebrate diverse perspectives and foster an environment where everyone feels valued and empowered to contribute."
            </div>
            <p>- Michael Davis, Founder & CEO</p>
        </div>
    </section>

    <!-- Apply Section -->
    <section class="apply-section">
        <div class="apply-container">
            <h2>Ready to Make an Impact?</h2>
            <p>Join our team and help us democratize data education. We're always looking for talented individuals who share our passion.</p>
            <a href="#" class="apply-button">View All Openings</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Job opening apply button handler
        document.querySelectorAll('.opening-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const jobTitle = this.closest('.opening-card').querySelector('.opening-title').textContent;
                alert('Opening application for: ' + jobTitle);
                // In a real application, this would open an application form
            });
        });

        // Apply now button handler
        document.querySelector('.apply-button').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Opening all available positions');
            // In a real application, this would scroll to the openings section or navigate to a dedicated jobs page
        });
    </script>
</body>
</html>