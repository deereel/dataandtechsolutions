<?php
/**
 * Data Tutors - 404 Page Not Found
 * Custom 404 error page with helpful suggestions
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Page Not Found');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="The page you are looking for could not be found. Check the URL or navigate to our homepage.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .error-hero {
            padding: 8rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-hero::before {
            content: '404';
            position: absolute;
            font-size: 20rem;
            font-weight: 900;
            opacity: 0.05;
            top: -5rem;
            left: 50%;
            transform: translateX(-50%);
            line-height: 1;
        }

        .error-hero h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
            position: relative;
            z-index: 1;
        }

        .error-hero p {
            font-size: 1.5rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 1;
        }

        .error-content {
            padding: 6rem 0;
            background: white;
        }

        .content-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .error-card {
            background: white;
            padding: 3rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            text-align: center;
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: var(--gray-50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--primary);
        }

        .error-card h2 {
            font-size: 2rem;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .error-card p {
            color: var(--gray-600);
            font-size: 1.125rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .quick-links {
            margin-top: 4rem;
            padding-top: 3rem;
            border-top: 1px solid var(--gray-200);
        }

        .quick-links h3 {
            font-size: 1.25rem;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
        }

        .quick-links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .quick-link {
            text-align: center;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            transition: var(--transition);
            text-decoration: none;
            color: var(--gray-700);
        }

        .quick-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .quick-link i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        @media (max-width: 768px) {
            .error-hero {
                padding: 6rem 0;
            }

            .error-hero h1 {
                font-size: 3rem;
            }

            .error-hero p {
                font-size: 1.25rem;
            }

            .error-content {
                padding: 4rem 0;
            }

            .error-card {
                padding: 2rem;
            }

            .error-icon {
                width: 80px;
                height: 80px;
                font-size: 3rem;
            }

            .error-card h2 {
                font-size: 1.5rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .quick-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="error-hero">
        <div class="container">
            <h1>404</h1>
            <p>Oops! Page Not Found</p>
        </div>
    </section>

    <!-- Error Content -->
    <section class="error-content">
        <div class="content-container">
            <div class="error-card">
                <div class="error-icon">
                    <i>🔍</i>
                </div>
                <h2>Page Not Found</h2>
                <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                
                <div class="error-actions">
                    <a href="<?= APP_URL ?>" class="btn btn-primary">
                        <i>🏠</i> Go to Homepage
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i>←</i> Back to Previous Page
                    </a>
                </div>
            </div>

            <div class="quick-links">
                <h3>Quick Links</h3>
                <div class="quick-links-grid">
                    <a href="<?= APP_URL ?>" class="quick-link">
                        <i>🏠</i>
                        <div>Home</div>
                    </a>
                    <a href="<?= APP_URL ?>/course" class="quick-link">
                        <i>📚</i>
                        <div>Courses</div>
                    </a>
                    <a href="<?= APP_URL ?>/forum" class="quick-link">
                        <i>💬</i>
                        <div>Forum</div>
                    </a>
                    <a href="<?= APP_URL ?>/pricing.php" class="quick-link">
                        <i>💲</i>
                        <div>Pricing</div>
                    </a>
                    <a href="<?= APP_URL ?>/about.php" class="quick-link">
                        <i>👥</i>
                        <div>About Us</div>
                    </a>
                    <a href="<?= APP_URL ?>/contact.php" class="quick-link">
                        <i>📞</i>
                        <div>Contact</div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Track 404 error
        console.error('404 Error: Page not found');

        // Add animation to error icon
        const errorIcon = document.querySelector('.error-icon');
        errorIcon.style.animation = 'pulse 2s infinite';

        // Add click handlers
        document.querySelector('.btn-primary').addEventListener('click', function() {
            window.location.href = '<?= APP_URL ?>';
        });

        document.querySelector('.btn-secondary').addEventListener('click', function() {
            history.back();
        });

        // Add hover effects to quick links
        document.querySelectorAll('.quick-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
