<?php
/* --------------------------------------------------------------
   Tech Solutions – Services Page
   -------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Tech Solutions | Data Tutors & Tech Solutions</title>
    <meta name="description" content="Custom website development, automation, mobile apps, and digital transformation services.">
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
            <a href="services.php" class="nav-link active">Services</a>
            <a href="portfolio.php" class="nav-link">Portfolio</a>
            <a href="blog.php" class="nav-link">Blog</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="contact.php" class="btn btn-sm btn-primary nav-cta">Get Free Consultation</a>
        </nav>
    </nav>

    <!-- ==================== HERO ==================== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Services That Transform Businesses</h1>
                    <p class="hero-subtitle">From intelligent automation to custom web applications, we build the technology infrastructure that powers modern business growth.</p>
                    <div class="hero-buttons">
                        <a href="#websites" class="btn btn-primary">Explore Services</a>
                        <a href="contact.php" class="btn btn-secondary">Get A Quote</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="../assets/images/automation.png" alt="Services Overview" style="width:100%;max-width:500px;border-radius:var(--radius-2xl);box-shadow:var(--shadow-2xl);">
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== WEBSITE DEVELOPMENT ==================== -->
    <section id="websites" class="service-detail">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(59 130 246 / 0.1);color:var(--blue-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Website Development</span>
                    <h2 style="margin-bottom:1rem;">Custom Websites That Convert Visitors Into Customers</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">We design and develop high-performance websites tailored to your business goals. Every site we build is optimized for speed, conversion, and scalability.</p>
                    <a href="contact.php" class="btn btn-primary">Start Your Project</a>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">🏢</div>
                        <h4 style="font-size:1rem;">Business Sites</h4>
                    </div>
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">🛍️</div>
                        <h4 style="font-size:1rem;">E-Commerce</h4>
                    </div>
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">📝</div>
                        <h4 style="font-size:1rem;">Blogs & CMS</h4>
                    </div>
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">🎨</div>
                        <h4 style="font-size:1rem;">Landing Pages</h4>
                    </div>
                </div>
            </div>
            <div class="service-features" style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:3rem;">
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div><strong>Responsive Design</strong><p style="font-size:0.9rem;">Pixel-perfect on every device and screen size.</p></div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div><strong>SEO Optimized</strong><p style="font-size:0.9rem;">Built to rank and drive organic traffic.</p></div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div><strong>Fast Loading</strong><p style="font-size:0.9rem;">Optimized performance for Core Web Vitals.</p></div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div><strong>CMS Integration</strong><p style="font-size:0.9rem;">Easy content management for your team.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== BUSINESS AUTOMATION ==================== -->
    <section id="automation" class="service-detail section-alt">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(139 92 246 / 0.1);color:var(--purple-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Automation</span>
                    <h2 style="margin-bottom:1rem;">Eliminate Manual Work With Intelligent Automation</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Stop wasting time on repetitive tasks. We design and implement powerful business automation systems that work 24/7 so your team can focus on what matters most.</p>
                    <a href="contact.php" class="btn btn-primary">Automate Your Business</a>
                </div>
                <div>
                    <div class="process-steps-visual" style="display:flex;flex-direction:column;gap:1rem;">
                        <div class="process-step-badge">Email Automation</div>
                        <div class="process-step-badge">CRM Integration</div>
                        <div class="process-step-badge">Lead Management</div>
                        <div class="process-step-badge">Data Sync</div>
                        <div class="process-step-badge">Reporting</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== n8n AUTOMATION ==================== -->
    <section id="n8n" class="service-detail">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(6 182 212 / 0.1);color:var(--cyan-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">n8n</span>
                    <h2 style="margin-bottom:1rem;">Powerful n8n Workflow Automation</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Build custom automation pipelines with n8n that connect your APIs, databases, and SaaS tools. Self-hosted or cloud—fully tailored to your infrastructure.</p>
                    <a href="contact.php" class="btn btn-primary">Get Started with n8n</a>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">What We Build:</h4>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Custom workflow creation</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> API integrations & webhooks</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Database automation</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> AI-powered decision flows</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Error handling & monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ZAPIER AUTOMATION ==================== -->
    <section id="zapier" class="service-detail section-alt">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(245 158 11 / 0.1);color:#d97706;border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Zapier</span>
                    <h2 style="margin-bottom:1rem;">Quick Integrations With Zapier</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Connect your favorite apps and automate workflows without code. Perfect for teams that need reliable, fast solutions without the overhead of custom development.</p>
                    <a href="contact.php" class="btn btn-primary">Automate With Zapier</a>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">Common Use Cases:</h4>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Lead capture to CRM automation</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Email marketing triggers</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Form submission workflows</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Calendar & scheduling automation</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Invoice & receipt processing</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MAKE.COM AUTOMATION ==================== -->
    <section id="make" class="service-detail">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(16 185 129 / 0.1);color:#059669;border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Make.com</span>
                    <h2 style="margin-bottom:1rem;">Advanced Workflows With Make.com</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">For complex, multi-step processes that need visual modeling and deep logic. Make.com gives us the power to build enterprise-grade automations with a visual interface.</p>
                    <a href="contact.php" class="btn btn-primary">Build With Make.com</a>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">Best For:</h4>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Multi-step approval workflows</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Data transformation pipelines</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Complex routing & branching</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Error handling & retry logic</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Bulk data operations</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== AIRTABLE SOLUTIONS ==================== -->
    <section id="airtable" class="service-detail section-alt">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(59 130 246 / 0.1);color:var(--blue-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Airtable</span>
                    <h2 style="margin-bottom:1rem;">Custom Airtable Databases & Systems</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Transform Airtable into your central business platform. We build custom databases, CRMs, project management systems, and internal tools that your team will actually want to use.</p>
                    <a href="contact.php" class="btn btn-primary">Build Your Airtable System</a>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">Solutions We Deliver:</h4>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Custom CRM systems</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Project management dashboards</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Inventory management</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Internal business systems</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Client portal & onboarding</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MOBILE APP DEVELOPMENT ==================== -->
    <section id="mobile" class="service-detail">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(139 92 246 / 0.1);color:var(--purple-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Mobile Apps</span>
                    <h2 style="margin-bottom:1rem;">Native & Cross-Platform Mobile Apps</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Build powerful mobile experiences for iOS and Android. From concept to app store, we handle everything—design, development, testing, and deployment.</p>
                    <a href="contact.php" class="btn btn-primary">Start Your App</a>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">🍎</div>
                        <h4 style="font-size:1rem;">iOS Apps</h4>
                        <p style="font-size:0.85rem;color:var(--slate-500);">Native Swift/SwiftUI development</p>
                    </div>
                    <div class="card" style="padding:1.5rem;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:0.75rem;">🤖</div>
                        <h4 style="font-size:1rem;">Android Apps</h4>
                        <p style="font-size:0.85rem;color:var(--slate-500);">Native Kotlin/Java development</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== WEB APPLICATIONS ==================== -->
    <section id="webapps" class="service-detail section-alt">
        <div class="container">
            <div class="service-hero" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <span style="display:inline-block;padding:0.4rem 1rem;background:rgb(6 182 212 / 0.1);color:var(--cyan-600);border-radius:var(--radius-full);font-size:0.85rem;font-weight:600;margin-bottom:1rem;">Web Apps</span>
                    <h2 style="margin-bottom:1rem;">Custom Web Applications & Dashboards</h2>
                    <p style="font-size:1.05rem;margin-bottom:2rem;">Complex business logic deserves elegant interfaces. We build full-stack web applications, admin dashboards, and customer portals that are fast, secure, and intuitive.</p>
                    <a href="contact.php" class="btn btn-primary">Build Your Web App</a>
                </div>
                <div class="card" style="padding:2rem;">
                    <h4 style="margin-bottom:1rem;">What We Deliver:</h4>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Admin dashboards & analytics</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Customer portal systems</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> SaaS applications</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Booking & scheduling systems</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="color:var(--blue-600);">→</span> Enterprise management tools</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA SECTION ==================== -->
    <section class="cta-section section">
        <div class="container">
            <h2>Ready To Digitally Transform Your Business?</h2>
            <p>Let's discuss your project and find the perfect solution for your needs.</p>
            <div class="cta-actions">
                <a href="contact.php" class="btn btn-primary btn-lg">Book Free Consultation</a>
                <a href="contact.php" class="btn btn-secondary btn-lg">Contact Us</a>
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
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="services.php#websites">Website Development</a></li>
                        <li><a href="services.php#automation">Automation</a></li>
                        <li><a href="services.php#n8n">n8n</a></li>
                        <li><a href="services.php#zapier">Zapier</a></li>
                        <li><a href="services.php#airtable">Airtable</a></li>
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
