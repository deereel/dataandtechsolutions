<?php
/**
 * Data Tutors - Header Include
 * Common header for all pages
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current page for active navigation
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_page = $current_page ? $current_page : 'index';

$user = isLoggedIn() ? getCurrentUser() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#2563eb">
    <meta name="description" content="Data Tutors - Master Excel, Data Analysis, and Data Automation with our comprehensive online courses">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Data Tutors">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-tap-highlight" content="no">
    
    <title><?= defined('PAGE_TITLE') ? PAGE_TITLE . ' | ' : '' ?><?= APP_NAME ?></title>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= APP_URL ?>/pwa/manifest.json">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APP_URL ?>/assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>/assets/images/logo.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
</head>
<body>
    <!-- PWA Install Banner -->
    <div id="install-banner" class="hidden" style="
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--primary);
        color: white;
        padding: 1rem;
        text-align: center;
        z-index: 9999;
    ">
        <span>Install Data Tutors for a better experience!</span>
        <button onclick="App.installPWA()" style="
            background: white;
            color: var(--primary);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            margin-left: 1rem;
            cursor: pointer;
            font-weight: 600;
        ">Install</button>
        <button onclick="document.getElementById('install-banner').classList.add('hidden')" style="
            background: transparent;
            color: white;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
        ">×</button>
    </div>
    
    <!-- Header -->
    <header class="header">
        <div class="container header-inner">
            <a href="<?= APP_URL ?>" class="logo">
                <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Data Tutors" class="logo-image" style="height: 40px; width: auto;">
            </a>
            
            <nav class="nav <?= isLoggedIn() ? '' : '' ?>">
                <a href="<?= APP_URL ?>/index.php" class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>">Home</a>
                <a href="<?= APP_URL ?>/course/index.php" class="nav-link <?= $current_page === 'course-index' || $current_page === 'course-details' ? 'active' : '' ?>">Courses</a>
                <a href="<?= APP_URL ?>/dashboard/index.php" class="nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>">My Learning</a>
                <a href="<?= APP_URL ?>/forum/index.php" class="nav-link <?= $current_page === 'forum' ? 'active' : '' ?>">Forum</a>
                
                <div class="nav-actions">
                    <?php if (isLoggedIn()): ?>
                    <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
                        <a href="<?= APP_URL ?>/dashboard/profile.php" class="btn btn-sm btn-secondary">
                            <span style="font-weight: 500;"><?= sanitize($user['name']) ?></span>
                        </a>
                        <a href="<?= APP_URL ?>/auth/logout.php" class="btn btn-sm btn-outline">Logout</a>
                    </div>
                    <?php else: ?>
                    <a href="<?= APP_URL ?>/auth/login.php" class="btn btn-secondary">Login</a>
                    <a href="<?= APP_URL ?>/auth/register.php" class="btn btn-primary">Get Started</a>
                    <?php endif; ?>
                </div>
            </nav>
            
            <button class="mobile-menu-btn" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
