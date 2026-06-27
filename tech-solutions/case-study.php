<?php
/* --------------------------------------------------------------
   Tech Solutions – Case Study Page
   -------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Study | Tech Solutions | Data Tutors & Tech Solutions</title>
    <meta name="description" content="Detailed case study of our technology solutions and business impact.">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script defer src="../../assets/js/main.js"></script>
</head>
<body>
    <!-- ==================== NAVIGATION ==================== -->
    <nav class="navbar">
        <a href="index.php" class="nav-logo">
            <img src="../assets/images/logo-tech.png" alt="Data Tutors & Tech Solutions">
            Tech Solutions
        </a>
        <button class="mobile-toggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="about.php" class="nav-link">About</a>
            <a href="services.php" class="nav-link">Services</a>
            <a href="portfolio.php" class="nav-link active">Portfolio</a>
            <a href="blog.php" class="nav-link">Blog</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="contact.php" class="btn btn-sm btn-primary nav-cta">Get Free Consultation</a>
        </nav>
    </nav>

    <!-- ==================== CASE STUDY HERO ==================== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(59 130 246 / 0.1);color:var(--blue-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Case Study</span>
                    <h1 class="hero-title">E-Commerce Platform Transformation</h1>
                    <p class="hero-subtitle">How we helped Luxe Fashion increase online revenue by 145% through a complete digital platform overhaul.</p>
                    <div class="hero-buttons">
                        <a href="#results" class="btn btn-primary">See Results</a>
                        <a href="portfolio.php" class="btn btn-secondary">All Projects</a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="portfolio-image" style="aspect-ratio:16/10;border-radius:var(--radius-2xl);"><span style="font-size:6rem;">🛒</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== PROJECT OVERVIEW ==================== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Project Overview</h2>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-bottom:4rem;">
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">Client Background</h4>
                    <p style="font-size:0.95rem;">Luxe Fashion is a premium clothing brand with 12 physical retail locations. Their legacy e-commerce platform was built on outdated technology, resulting in slow performance, poor mobile experience, and declining conversion rates.</p>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">Business Challenge</h4>
                    <p style="font-size:0.95rem;">The client needed a modern, scalable e-commerce platform that could handle seasonal traffic spikes, integrate with their POS systems, and provide a premium shopping experience matching their brand identity.</p>
                </div>
            </div>

            <div class="card" style="padding:2.5rem;margin-bottom:4rem;background:linear-gradient(135deg, rgb(59 130 246 / 0.02), rgb(139 92 246 / 0.02));">
                <h4 style="margin-bottom:1rem;">Objectives</h4>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;">
                    <div style="text-align:center;">
                        <div style="font-size:2rem;font-weight:800;color:var(--blue-600);font-family:var(--font-display);">145%</div>
                        <div style="font-size:0.9rem;color:var(--slate-600);">Revenue Increase</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:2rem;font-weight:800;color:var(--purple-600);font-family:var(--font-display);">< 2s</div>
                        <div style="font-size:0.9rem;color:var(--slate-600);">Page Load Time</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:2rem;font-weight:800;color:var(--cyan-600);font-family:var(--font-display);">+68%</div>
                        <div style="font-size:0.9rem;color:var(--slate-600);">Mobile Conversions</div>
                    </div>
                </div>
            </div>

            <h3 style="margin-bottom:2rem;">Solution Strategy</h3>
            <p style="font-size:1.05rem;margin-bottom:3rem;">We architected a complete e-commerce solution using modern PHP and JavaScript technologies. The platform features a custom checkout flow, real-time inventory sync, personalized recommendations, and seamless POS integration.</p>

            <h3 style="margin-bottom:2rem;">Technology Stack</h3>
            <div class="tech-stack" style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:3rem;">
                <span class="tech-pill">PHP 8.2</span>
                <span class="tech-pill">JavaScript ES6+</span>
                <span class="tech-pill">MySQL</span>
                <span class="tech-pill">Stripe API</span>
                <span class="tech-pill">Redis</span>
                <span class="tech-pill">AWS</span>
                <span class="tech-pill">CloudFlare</span>
            </div>
        </div>
    </section>

    <!-- ==================== RESULTS ==================== -->
    <section id="results" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Measurable Results</h2>
                <p>Real business impact delivered in the first 3 months.</p>
            </div>
            <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);gap:2rem;text-align:center;">
                <div class="stat-item">
                    <span class="stat-number">145%</span>
                    <span class="stat-label">Revenue Increase</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">68%</span>
                    <span class="stat-label">Mobile Conversion Lift</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">45%</span>
                    <span class="stat-label">Cart Abandonment Reduction</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">2.1s</span>
                    <span class="stat-label">Average Load Time</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CLIENT TESTIMONIAL ==================== -->
    <section class="section">
        <div class="container">
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Tech Solutions transformed our entire e-commerce operation. The new platform is fast, beautiful, and our customers love it. Sales have literally doubled since launch."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">MR</div>
                        <div class="testimonial-meta">
                            <strong>Michael Roberts</strong>
                            <span>CEO, Luxe Fashion</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA ==================== -->
    <section class="cta-section section">
        <div class="container">
            <h2>Ready For Similar Results?</h2>
            <p>Let's build your success story together.</p>
            <div class="cta-actions">
                <a href="contact.php" class="btn btn-primary btn-lg">Start Your Project</a>
                <a href="portfolio.php" class="btn btn-secondary btn-lg">View More Work</a>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">Tech Solutions</div>
                    <p class="footer-intro">Premium technology services for modern businesses.</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="portfolio.php">Portfolio</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact</h4>
                    <ul class="footer-contact">
                        <li>📞 (555) 123-4567</li>
                        <li>✉️ info@datatutorstech.com</li>
                        <li>📍 Silicon Valley, CA</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y'); ?> Tech Solutions. All rights reserved.</span>
                <a href="privacy.php" style="color:var(--slate-500);">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
