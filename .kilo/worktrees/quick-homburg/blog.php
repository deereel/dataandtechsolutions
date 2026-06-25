<?php
/**
 * Data Tutors - Blog Page
 * Display latest articles and blog posts
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Blog');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Latest articles and tutorials about Excel, data analysis, SQL, Python, Power BI, and data visualization.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .blog-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
            color: white;
            text-align: center;
        }

        .blog-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .blog-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .blog-section {
            padding: 6rem 0;
            background: white;
        }

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .blog-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .blog-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--gray-400);
        }

        .blog-content {
            padding: 1.5rem;
        }

        .blog-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: var(--primary);
            color: white;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .blog-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--gray-900);
            line-height: 1.4;
        }

        .blog-excerpt {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }

        .blog-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .blog-author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .blog-author-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .blog-author-name {
            font-weight: 500;
        }

        .blog-read-more {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .blog-read-more:hover {
            color: var(--primary-dark);
        }

        .blog-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 4rem;
        }

        .pagination-button {
            padding: 0.75rem 1.5rem;
            border: 1px solid var(--gray-200);
            background: white;
            color: var(--gray-700);
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .pagination-button:hover {
            background: var(--gray-50);
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination-button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .sidebar {
            grid-column: span 3;
        }

        .sidebar-section {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            margin-bottom: 2rem;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.9375rem;
        }

        .search-button {
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-dark);
        }

        .category-list {
            list-style: none;
        }

        .category-item {
            padding: 0.75rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-600);
            transition: var(--transition);
            cursor: pointer;
            border-bottom: 1px solid var(--gray-100);
        }

        .category-item:hover {
            color: var(--primary);
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-count {
            background: var(--gray-100);
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .recent-posts {
            list-style: none;
        }

        .recent-post-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition);
        }

        .recent-post-item:hover {
            background: var(--gray-50);
            border-radius: var(--radius);
        }

        .recent-post-item:last-child {
            border-bottom: none;
        }

        .recent-post-thumbnail {
            width: 80px;
            height: 80px;
            background: var(--gray-100);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gray-400);
            flex-shrink: 0;
        }

        .recent-post-info h4 {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .recent-post-info .date {
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        @media (min-width: 1024px) {
            .blog-main {
                display: grid;
                grid-template-columns: 1fr 350px;
                gap: 3rem;
            }
        }

        @media (max-width: 768px) {
            .blog-hero {
                padding: 4rem 0;
            }

            .blog-hero h1 {
                font-size: 2rem;
            }

            .blog-hero p {
                font-size: 1rem;
            }

            .blog-section {
                padding: 4rem 0;
            }

            .blog-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .blog-meta {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .sidebar-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1>Data Tutors Blog</h1>
            <p>Latest articles, tutorials, and insights about Excel, data analysis, SQL, Python, and Power BI.</p>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="blog-section">
        <div class="blog-container">
            <div class="blog-main">
                <!-- Main Content -->
                <div class="blog-grid">
                    <!-- Blog Post 1 -->
                    <article class="blog-card">
                        <div class="blog-image">📊</div>
                        <div class="blog-content">
                            <span class="blog-category">Excel Tutorial</span>
                            <h3 class="blog-title">Advanced Pivot Table Techniques for Data Analysis</h3>
                            <p class="blog-excerpt">Master powerful pivot table techniques to analyze large datasets, create interactive dashboards, and uncover hidden insights in your data.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">MD</div>
                                    <span class="blog-author-name">Michael Davis</span>
                                </div>
                                <div class="blog-date">Feb 20, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>

                    <!-- Blog Post 2 -->
                    <article class="blog-card">
                        <div class="blog-image">🔍</div>
                        <div class="blog-content">
                            <span class="blog-category">SQL Tips</span>
                            <h3 class="blog-title">10 Essential SQL Queries Every Data Analyst Should Know</h3>
                            <p class="blog-excerpt">Learn the most commonly used SQL queries for data analysis, including filtering, sorting, aggregating, and joining data from multiple tables.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">RK</div>
                                    <span class="blog-author-name">Rajesh Kumar</span>
                                </div>
                                <div class="blog-date">Feb 18, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>

                    <!-- Blog Post 3 -->
                    <article class="blog-card">
                        <div class="blog-image">📈</div>
                        <div class="blog-content">
                            <span class="blog-category">Data Visualization</span>
                            <h3 class="blog-title">Power BI Dashboard Design Best Practices</h3>
                            <p class="blog-excerpt">Create beautiful, effective Power BI dashboards with these proven design principles and techniques used by professional BI developers.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">LM</div>
                                    <span class="blog-author-name">Laura Martinez</span>
                                </div>
                                <div class="blog-date">Feb 15, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>

                    <!-- Blog Post 4 -->
                    <article class="blog-card">
                        <div class="blog-image">🐍</div>
                        <div class="blog-content">
                            <span class="blog-category">Python Tutorial</span>
                            <h3 class="blog-title">Introduction to Pandas for Data Analysis</h3>
                            <p class="blog-excerpt">Get started with Pandas, the powerful Python library for data manipulation and analysis. Learn how to load, clean, and analyze data using DataFrames.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">MD</div>
                                    <span class="blog-author-name">Michael Davis</span>
                                </div>
                                <div class="blog-date">Feb 12, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>

                    <!-- Blog Post 5 -->
                    <article class="blog-card">
                        <div class="blog-image">💰</div>
                        <div class="blog-content">
                            <span class="blog-category">Career Advice</span>
                            <h3 class="blog-title">How to Become a Data Analyst Without a Degree</h3>
                            <p class="blog-excerpt">Learn the skills, certifications, and portfolio projects you need to launch a successful career in data analytics without a traditional degree.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">SJ</div>
                                    <span class="blog-author-name">Sarah Johnson</span>
                                </div>
                                <div class="blog-date">Feb 10, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>

                    <!-- Blog Post 6 -->
                    <article class="blog-card">
                        <div class="blog-image">🔄</div>
                        <div class="blog-content">
                            <span class="blog-category">Data Automation</span>
                            <h3 class="blog-title">Automating Excel Reports with Power Query</h3>
                            <p class="blog-excerpt">Save hours of manual work by automating your Excel reporting process using Power Query. Learn how to create reusable data connections and refreshable reports.</p>
                            <div class="blog-meta">
                                <div class="blog-author">
                                    <div class="blog-author-avatar">SJ</div>
                                    <span class="blog-author-name">Sarah Johnson</span>
                                </div>
                                <div class="blog-date">Feb 8, 2026</div>
                            </div>
                            <a href="#" class="blog-read-more">Read More →</a>
                        </div>
                    </article>
                </div>

                <!-- Pagination -->
                <div class="blog-pagination">
                    <a href="#" class="pagination-button">← Previous</a>
                    <a href="#" class="pagination-button active">1</a>
                    <a href="#" class="pagination-button">2</a>
                    <a href="#" class="pagination-button">3</a>
                    <a href="#" class="pagination-button">Next →</a>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Search Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Search</h3>
                    <form class="search-form">
                        <input type="text" placeholder="Search articles..." class="search-input">
                        <button type="submit" class="search-button">Search</button>
                    </form>
                </div>

                <!-- Categories Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="category-list">
                        <li class="category-item">
                            <span>Excel Tutorials</span>
                            <span class="category-count">25</span>
                        </li>
                        <li class="category-item">
                            <span>SQL Tips</span>
                            <span class="category-count">18</span>
                        </li>
                        <li class="category-item">
                            <span>Python Tutorials</span>
                            <span class="category-count">12</span>
                        </li>
                        <li class="category-item">
                            <span>Power BI</span>
                            <span class="category-count">15</span>
                        </li>
                        <li class="category-item">
                            <span>Data Analysis</span>
                            <span class="category-count">20</span>
                        </li>
                        <li class="category-item">
                            <span>Career Advice</span>
                            <span class="category-count">8</span>
                        </li>
                        <li class="category-item">
                            <span>Data Automation</span>
                            <span class="category-count">10</span>
                        </li>
                    </ul>
                </div>

                <!-- Recent Posts Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Recent Posts</h3>
                    <ul class="recent-posts">
                        <li class="recent-post-item">
                            <div class="recent-post-thumbnail">📊</div>
                            <div class="recent-post-info">
                                <h4>Advanced Pivot Table Techniques</h4>
                                <span class="date">Feb 20, 2026</span>
                            </div>
                        </li>
                        <li class="recent-post-item">
                            <div class="recent-post-thumbnail">🔍</div>
                            <div class="recent-post-info">
                                <h4>10 Essential SQL Queries</h4>
                                <span class="date">Feb 18, 2026</span>
                            </div>
                        </li>
                        <li class="recent-post-item">
                            <div class="recent-post-thumbnail">📈</div>
                            <div class="recent-post-info">
                                <h4>Power BI Dashboard Design</h4>
                                <span class="date">Feb 15, 2026</span>
                            </div>
                        </li>
                        <li class="recent-post-item">
                            <div class="recent-post-thumbnail">🐍</div>
                            <div class="recent-post-info">
                                <h4>Introduction to Pandas</h4>
                                <span class="date">Feb 12, 2026</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter Section -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Stay Updated</h3>
                    <p style="color: var(--gray-600); margin-bottom: 1rem; font-size: 0.9375rem;">Subscribe to our newsletter for the latest articles and tutorials.</p>
                    <form>
                        <input type="email" placeholder="Your email address" class="search-input" style="margin-bottom: 0.5rem;">
                        <button type="submit" class="search-button" style="width: 100%;">Subscribe</button>
                    </form>
                </div>
            </aside>
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

        // Newsletter subscription
        document.querySelector('.sidebar-section:last-child form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            if (email.trim()) {
                alert('Thank you for subscribing!');
                this.reset();
            }
        });
    </script>
</body>
</html>