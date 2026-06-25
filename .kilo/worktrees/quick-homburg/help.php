<?php
/**
 * Data Tutors - Help & Support Center
 * Comprehensive help center with guides, tutorials, and support resources
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Help Center');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Get help and support for Data Tutors courses. Find answers, tutorials, and contact our support team.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .help-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .help-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .help-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-section {
            padding: 4rem 0;
            background: white;
            border-bottom: 1px solid var(--gray-200);
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .search-input {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 1.125rem;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .search-button {
            padding: 1rem 2rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.125rem;
        }

        .search-button:hover {
            background: var(--primary-dark);
        }

        .search-suggestions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .suggestion-tag {
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            color: var(--gray-600);
            border-radius: 50px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .suggestion-tag:hover {
            background: var(--primary);
            color: white;
        }

        .help-categories {
            padding: 6rem 0;
            background: var(--gray-50);
        }

        .categories-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .categories-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .categories-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .categories-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .category-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            text-align: center;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .category-icon {
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

        .category-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .category-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }

        .category-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }

        .category-link:hover {
            color: var(--primary-dark);
            gap: 0.75rem;
        }

        .featured-articles {
            padding: 6rem 0;
            background: white;
        }

        .articles-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .articles-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .articles-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .articles-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .article-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .article-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--gray-900);
            line-height: 1.4;
        }

        .article-card p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
            margin-bottom: 1rem;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .contact-section {
            padding: 6rem 0;
            background: var(--gray-900);
            color: white;
            text-align: center;
        }

        .contact-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .contact-container h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .contact-container p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin-bottom: 2rem;
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
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .help-hero {
                padding: 4rem 0;
            }

            .help-hero h1 {
                font-size: 2rem;
            }

            .help-hero p {
                font-size: 1rem;
            }

            .search-section,
            .help-categories,
            .featured-articles,
            .contact-section {
                padding: 4rem 0;
            }

            .categories-header h2,
            .articles-header h2,
            .contact-container h2 {
                font-size: 2rem;
            }

            .categories-grid,
            .articles-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .search-suggestions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="help-hero">
        <div class="container">
            <h1>Help & Support</h1>
            <p>Get answers to your questions and find resources to help you succeed in your learning journey.</p>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="search-container">
            <form class="search-form">
                <input type="text" placeholder="Search for help articles..." class="search-input">
                <button type="submit" class="search-button">Search</button>
            </form>
            <div class="search-suggestions">
                <span class="suggestion-tag">Excel formulas</span>
                <span class="suggestion-tag">Power BI</span>
                <span class="suggestion-tag">SQL queries</span>
                <span class="suggestion-tag">Python pandas</span>
                <span class="suggestion-tag">Account settings</span>
                <span class="suggestion-tag">Course access</span>
            </div>
        </div>
    </section>

    <!-- Help Categories -->
    <section class="help-categories">
        <div class="categories-container">
            <div class="categories-header">
                <h2>Browse Help Categories</h2>
                <p>Find help by category or explore our featured articles</p>
            </div>

            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon">🎓</div>
                    <h3>Getting Started</h3>
                    <p>New to Data Tutors? Learn how to set up your account, navigate the platform, and start learning.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>

                <div class="category-card">
                    <div class="category-icon">💻</div>
                    <h3>Technical Support</h3>
                    <p>Troubleshooting guides for common technical issues with the platform and course materials.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>

                <div class="category-card">
                    <div class="category-icon">📚</div>
                    <h3>Course Help</h3>
                    <p>Get help with specific courses, lessons, quizzes, and assessments.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>

                <div class="category-card">
                    <div class="category-icon">💰</div>
                    <h3>Billing & Payments</h3>
                    <p>Questions about pricing, payments, refunds, and subscription management.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>

                <div class="category-card">
                    <div class="category-icon">🎯</div>
                    <h3>Learning Tips</h3>
                    <p>Best practices, study strategies, and productivity tips for effective learning.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>

                <div class="category-card">
                    <div class="category-icon">📜</div>
                    <h3>Certificates</h3>
                    <p>Information about certificates, how to earn them, and how to share them.</p>
                    <a href="#" class="category-link">Browse Articles →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Articles -->
    <section class="featured-articles">
        <div class="articles-container">
            <div class="articles-header">
                <h2>Featured Articles</h2>
                <p>Most popular and helpful articles for our learners</p>
            </div>

            <div class="articles-grid">
                <article class="article-card">
                    <h3>How to Download Course Exercise Files</h3>
                    <p>Step-by-step guide to downloading and accessing course resources and exercise files.</p>
                    <div class="article-meta">
                        <span>5 min read</span>
                        <span>Updated 2 days ago</span>
                    </div>
                </article>

                <article class="article-card">
                    <h3>Troubleshooting Video Playback Issues</h3>
                    <p>Common video playback problems and how to fix them on different devices.</p>
                    <div class="article-meta">
                        <span>3 min read</span>
                        <span>Updated 1 week ago</span>
                    </div>
                </article>

                <article class="article-card">
                    <h3>How to Earn Your Certificate</h3>
                    <p>Complete guide to earning and downloading your course completion certificate.</p>
                    <div class="article-meta">
                        <span>4 min read</span>
                        <span>Updated 3 days ago</span>
                    </div>
                </article>

                <article class="article-card">
                    <h3>Resetting Your Password</h3>
                    <p>Step-by-step instructions for resetting your password and account recovery.</p>
                    <div class="article-meta">
                        <span>2 min read</span>
                        <span>Updated 1 week ago</span>
                    </div>
                </article>

                <article class="article-card">
                    <h3>Understanding Course Progress Tracking</h3>
                    <p>Learn how we track your progress and what counts towards course completion.</p>
                    <div class="article-meta">
                        <span>6 min read</span>
                        <span>Updated 4 days ago</span>
                    </div>
                </article>

                <article class="article-card">
                    <h3>Refund Policy and Process</h3>
                    <p>Everything you need to know about our 30-day money-back guarantee and refund process.</p>
                    <div class="article-meta">
                        <span>3 min read</span>
                        <span>Updated 5 days ago</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <h2>Still Need Help?</h2>
            <p>Can't find what you're looking for? Our support team is here to assist you.</p>
            <a href="<?= APP_URL ?>/contact.php" class="contact-button">Contact Support</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Search functionality
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('.search-input').value;
            if (searchTerm.trim()) {
                alert('Searching for: ' + searchTerm);
                // Add actual search logic here
            }
        });

        // Search suggestion tags
        document.querySelectorAll('.suggestion-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                const searchInput = document.querySelector('.search-input');
                searchInput.value = this.textContent;
                searchInput.focus();
            });
        });

        // Category card click handler
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                // In a real application, this would navigate to the category page
                alert('Navigating to ' + this.querySelector('h3').textContent + ' articles');
            });
        });

        // Article card click handler
        document.querySelectorAll('.article-card').forEach(card => {
            card.addEventListener('click', function() {
                // In a real application, this would open the article
                alert('Opening article: ' + this.querySelector('h3').textContent);
            });
        });
    </script>
</body>
</html>