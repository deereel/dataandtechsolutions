<?php
/**
 * Data Tutors - 403 Forbidden
 * Custom 403 error page for forbidden access
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Forbidden');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="You don't have permission to access this page. Please login or contact support.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .error-hero {
            padding: 8rem 0;
            background: linear-gradient(135deg, var(--warning) 0%, var(--warning-dark) 100%);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .error-hero::before {
            content: '403';
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
            color: var(--warning);
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
            background: var(--warning);
            color: white;
        }

        .btn-primary:hover {
            background: var(--warning-dark);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: var(--warning);
            border: 2px solid var(--warning);
        }

        .btn-secondary:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-2px);
        }

        .btn-login {
            background: var(--primary);
            color: white;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .access-info {
            margin-top: 3rem;
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            text-align: left;
        }

        .access-info h3 {
            font-size: 1.25rem;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .access-info ul {
            list-style: none;
            padding: 0;
            color: var(--gray-600);
        }

        .access-info li {
            padding: 0.5rem 0;
            position: relative;
            padding-left: 1.5rem;
        }

        .access-info li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: var(--warning);
            font-weight: bold;
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
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="error-hero">
        <div class="container">
            <h1>403</h1>
            <p>Access Forbidden</p>
        </div>
    </section>

    <!-- Error Content -->
    <section class="error-content">
        <div class="content-container">
            <div class="error-card">
                <div class="error-icon">
                    <i>🔒</i>
                </div>
                <h2>Forbidden Access</h2>
                <p>You don't have permission to access this page. Please login with the appropriate credentials.</p>
                
                <div class="error-actions">
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?= APP_URL ?>/auth/login.php" class="btn btn-login">
                            <i>🔐</i> Login
                        </a>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>" class="btn btn-primary">
                        <i>🏠</i> Go to Homepage
                    </a>
                    <a href="<?= APP_URL ?>/contact.php" class="btn btn-secondary">
                        <i>📞</i> Contact Support
                    </a>
                </div>

                <div class="access-info">
                    <h3>Why this might happen:</h3>
                    <ul>
                        <li>You need to login to access this page</li>
                        <li>Your account doesn't have the required permissions</li>
                        <li>The page is restricted to certain user roles</li>
                        <li>Your session might have expired</li>
                        <li>The page doesn't exist or has been removed</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Track 403 error
        console.error('403 Error: Forbidden Access');

        // Add animation to error icon
        const errorIcon = document.querySelector('.error-icon');
        errorIcon.style.animation = 'bounce 1s infinite';

        // Login button handler
        const loginBtn = document.querySelector('.btn-login');
        if (loginBtn) {
            loginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '<?= APP_URL ?>/auth/login.php?redirect=' + encodeURIComponent(window.location.pathname);
            });
        }

        // Homepage button handler
        document.querySelector('.btn-primary').addEventListener('click', function() {
            window.location.href = '<?= APP_URL ?>';
        });

        // Support button handler
        document.querySelector('.btn-secondary').addEventListener('click', function() {
            window.location.href = '<?= APP_URL ?>/contact.php';
        });
    </script>
</body>
</html>
