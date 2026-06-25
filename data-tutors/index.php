<?php
/**
 * Data Tutors - Landing Page
 * Main homepage with hero, courses, testimonials, and pricing
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Get featured courses
try {
    $featuredCourses = Course::getFeatured();
} catch (Exception $e) {
    $featuredCourses = [];
}

// Page title
define('PAGE_TITLE', 'Home');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#2563eb">
    <meta name="description" content="Data Tutors - Master Excel, Data Analysis, and Data Automation with our comprehensive online courses. Start your journey to becoming a data professional today.">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Data Tutors">
    <link rel="manifest" href="<?= APP_URL ?>/pwa/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APP_URL ?>/assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title><?= APP_NAME ?> - Master Data Skills Online</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-inner">
            <a href="<?= APP_URL ?>" class="logo">
                <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Data Tutors" class="logo-image" style="height: 40px; width: auto;">
            </a>
            
            <nav class="nav">
                <a href="#courses" class="nav-link">Courses</a>
                <a href="#how-it-works" class="nav-link">How It Works</a>
                <a href="#testimonials" class="nav-link">Testimonials</a>
                <a href="#pricing" class="nav-link">Pricing</a>
                
                <div class="nav-actions">
                    <?php if (isLoggedIn()): ?>
                    <a href="<?= APP_URL ?>/dashboard/index.php" class="btn btn-sm btn-secondary">
                        <span style="font-weight: 500;"><?= sanitize($_SESSION['user_name'] ?? 'My Account') ?></span>
                    </a>
                    <a href="<?= APP_URL ?>/auth/logout.php" class="btn btn-sm btn-outline">Logout</a>
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
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-inner">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            <span>Learn from industry experts</span>
                        </div>
                        <h1 class="hero-title">
                            Master <span>Data Skills</span> That Advance Your Career
                        </h1>
                        <p class="hero-text">
                            Join thousands of professionals mastering Excel, Data Analysis, and Automation. 
                            Learn at your own pace with hands-on projects and earn certificates.
                        </p>
                        <div class="hero-actions">
                            <a href="/course/index.php" class="btn btn-primary btn-lg">
                                Browse Courses
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            <a href="#how-it-works" class="btn btn-outline btn-lg">
                                How It Works
                            </a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <div class="stat-number">10,000+</div>
                                <div class="stat-label">Students</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Lessons</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">5</div>
                                <div class="stat-label">Certificates</div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <div style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border-radius: 16px; padding: 2rem; aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center;">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                                <rect x="20" y="20" width="160" height="160" rx="12" fill="#2563eb"/>
                                <rect x="40" y="50" width="80" height="8" rx="4" fill="white" fill-opacity="0.6"/>
                                <rect x="40" y="70" width="120" height="6" rx="3" fill="white" fill-opacity="0.4"/>
                                <rect x="40" y="90" width="100" height="6" rx="3" fill="white" fill-opacity="0.4"/>
                                <rect x="40" y="110" width="110" height="6" rx="3" fill="white" fill-opacity="0.4"/>
                                <rect x="40" y="130" width="90" height="6" rx="3" fill="white" fill-opacity="0.4"/>
                                <circle cx="140" cy="145" r="30" fill="#10b981"/>
                                <path d="M130 145L137 152L150 139" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Courses Section -->
        <section id="courses" class="section" style="background: var(--gray-50);">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured Courses</h2>
                    <p class="section-subtitle">Start your data journey with our most popular courses</p>
                </div>
                
                <div class="courses-grid">
                    <!-- Excel Course -->
                    <div class="course-card">
                        <div class="course-image" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="white">
                                    <path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h4v2H7V7zm0 4h4v2H7v-2zm0 4h2v2H7v-2zm6-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2zm-4 4h4v2H9v-2zm0-8h4v2H9V7z"/>
                                </svg>
                            </div>
                            <span class="course-badge">Beginner</span>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">Excel Mastery</h3>
                            <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                                From basics to advanced formulas, pivot tables, and macros.
                            </p>
                            <div class="course-meta">
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    20 Hours
                                </span>
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    50+ Lessons
                                </span>
                            </div>
                            <div class="course-price">$49</div>
                            <div class="course-footer">
                                <a href="/course/details.php?slug=excel-mastery" class="btn btn-primary" style="flex: 1;">View Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Analysis Course -->
                    <div class="course-card">
                        <div class="course-image" style="background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);">
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="white">
                                    <path d="M3 3h18v18H3V3zm4 14h2V7H7v10zm4-4h2V7h-2v6zm4-6h2V7h-2v6z"/>
                                </svg>
                            </div>
                            <span class="course-badge">Intermediate</span>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">Data Analysis</h3>
                            <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                                Master SQL, Python basics, Power BI, and data visualization.
                            </p>
                            <div class="course-meta">
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    40 Hours
                                </span>
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    100+ Lessons
                                </span>
                            </div>
                            <div class="course-price">$79</div>
                            <div class="course-footer">
                                <a href="/course/details.php?slug=data-analysis" class="btn btn-primary" style="flex: 1;">View Details</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Automation Course -->
                    <div class="course-card">
                        <div class="course-image" style="background: linear-gradient(135deg, #ea580c 0%, #f59e0b 100%);">
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="white">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                </svg>
                            </div>
                            <span class="course-badge">Advanced</span>
                        </div>
                        <div class="course-content">
                            <h3 class="course-title">Data Automation</h3>
                            <p style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 1rem;">
                                Learn Zapier, Make.com, Airtable, and N8N for workflows.
                            </p>
                            <div class="course-meta">
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    30 Hours
                                </span>
                                <span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    75+ Lessons
                                </span>
                            </div>
                            <div class="course-price">$99</div>
                            <div class="course-footer">
                                <a href="/course/details.php?slug=data-automation" class="btn btn-primary" style="flex: 1;">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 3rem;">
                    <a href="/course/index.php" class="btn btn-outline btn-lg">
                        View All Courses
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Why Choose Data Tutors?</h2>
                    <p class="section-subtitle">Everything you need to become a data professional</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h4 class="feature-title">Comprehensive Curriculum</h4>
                        <p class="feature-text">From beginner to advanced, our courses cover everything you need to master data skills.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="feature-title">Video Lessons</h4>
                        <p class="feature-text">High-quality video content with embedded YouTube tutorials for easy learning.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h4 class="feature-title">Interactive Quizzes</h4>
                        <p class="feature-text">Test your knowledge with quizzes after each lesson to reinforce learning.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h4 class="feature-title">Community Forum</h4>
                        <p class="feature-text">Get help from instructors and fellow students in our Q&A forum.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <h4 class="feature-title">Certificates</h4>
                        <p class="feature-text">Earn verifiable certificates upon course completion to showcase your skills.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="feature-title">Learn at Your Pace</h4>
                        <p class="feature-text">Access courses 24/7 and track your progress as you learn on your schedule.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="section" style="background: var(--gray-50);">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">How It Works</h2>
                    <p class="section-subtitle">Start learning in 4 simple steps</p>
                </div>
                
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4 class="step-title">Enroll</h4>
                        <p class="step-text">Choose a course and enroll with one click. Free previews available.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4 class="step-title">Learn</h4>
                        <p class="step-text">Watch video lessons, read content, and practice with hands-on exercises.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4 class="step-title">Practice</h4>
                        <p class="step-text">Take quizzes and complete projects to reinforce your learning.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h4 class="step-title">Earn Certificate</h4>
                        <p class="step-text">Complete the course and earn a verifiable certificate.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">What Our Students Say</h2>
                    <p class="section-subtitle">Join thousands of satisfied learners</p>
                </div>
                
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <p class="testimonial-content">"The Excel course transformed my career. I went from basic spreadsheets to advanced pivot tables and macros. My productivity increased by 300%!"</p>
                        <div class="testimonial-author">
                            <div style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--primary);">AJ</div>
                            <div>
                                <div class="author-name">Amara Johnson</div>
                                <div class="author-role">Data Analyst, Tech Corp</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <p class="testimonial-content">"The Data Analysis course was exactly what I needed. SQL, Python, Power BI - everything covered. The instructor explains complex concepts so clearly."</p>
                        <div class="testimonial-author">
                            <div style="width: 48px; height: 48px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--success);">MK</div>
                            <div>
                                <div class="author-name">Michael Kim</div>
                                <div class="author-role">Business Intelligence Analyst</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <p class="testimonial-content">"The Automation course helped me automate my entire reporting workflow. What used to take hours now takes minutes. Best investment I've made!"</p>
                        <div class="testimonial-author">
                            <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--warning);">SO</div>
                            <div>
                                <div class="author-name">Sarah Okonkwo</div>
                                <div class="author-role">Operations Manager</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="section" style="background: var(--gray-50);">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Simple, Transparent Pricing</h2>
                    <p class="section-subtitle">Pay once, learn forever. No hidden fees.</p>
                </div>
                
                <div class="pricing-grid">
                    <div class="pricing-card">
                        <h3 class="pricing-title">Single Course</h3>
                        <div class="pricing-price">$49<span>/course</span></div>
                        <ul class="pricing-features">
                            <li>Access to one course</li>
                            <li>All course materials</li>
                            <li>Quizzes & assessments</li>
                            <li>Community forum access</li>
                            <li>Course certificate</li>
                            <li>Lifetime access</li>
                        </ul>
                        <a href="/course/index.php" class="btn btn-secondary btn-lg" style="width: 100%;">Get Started</a>
                    </div>
                    
                    <div class="pricing-card featured">
                        <h3 class="pricing-title">All Access</h3>
                        <div class="pricing-price">$149<span>/year</span></div>
                        <ul class="pricing-features">
                            <li>Access to all courses</li>
                            <li>All course materials</li>
                            <li>Quizzes & assessments</li>
                            <li>Priority forum support</li>
                            <li>All certificates</li>
                            <li>New courses included</li>
                            <li>Priority updates</li>
                        </ul>
                        <a href="/auth/register.php" class="btn btn-primary btn-lg" style="width: 100%;">Get Started</a>
                    </div>
                    
                    <div class="pricing-card">
                        <h3 class="pricing-title">Team</h3>
                        <div class="pricing-price">$499<span>/year</span></div>
                        <ul class="pricing-features">
                            <li>Up to 10 team members</li>
                            <li>All course access</li>
                            <li>Team progress tracking</li>
                            <li>Dedicated support</li>
                            <li>Custom certificates</li>
                            <li>Invoice billing</li>
                        </ul>
                        <a href="/contact.php" class="btn btn-secondary btn-lg" style="width: 100%;">Contact Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section" style="background: var(--primary); color: white;">
            <div class="container" style="text-align: center;">
                <h2 style="font-size: 2.25rem; margin-bottom: 1rem;">Ready to Start Learning?</h2>
                <p style="font-size: 1.125rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2rem;">
                    Join thousands of professionals advancing their careers with Data Tutors.
                </p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="/auth/register.php" class="btn btn-lg" style="background: white; color: var(--primary);">
                        Create Free Account
                    </a>
                    <a href="/course/index.php" class="btn btn-lg" style="background: transparent; border: 2px solid white; color: white;">
                        Browse Courses
                    </a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>