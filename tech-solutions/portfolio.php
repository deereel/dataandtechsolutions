<?php
/* --------------------------------------------------------------
   Tech Solutions – Portfolio Page
   -------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Tech Solutions | Data Tutors & Tech Solutions</title>
    <meta name="description" content="Explore our portfolio of custom websites, automation projects, mobile apps, and business systems.">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script defer src="../../assets/js/main.js"></script>
    <style>
        .portfolio-masonry {
            columns: 3;
            column-gap: 1.5rem;
        }
        @media (max-width: 968px) {
            .portfolio-masonry { columns: 2; }
        }
        @media (max-width: 640px) {
            .portfolio-masonry { columns: 1; }
        }
        .portfolio-item-masonry {
            break-inside: avoid;
            margin-bottom: 1.5rem;
        }
        .filter-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-select {
            padding: 0.6rem 1rem;
            border-radius: var(--radius-full);
            border: 1px solid var(--slate-200);
            background: white;
            font-family: var(--font-sans);
            font-size: 0.9rem;
            color: var(--slate-700);
            cursor: pointer;
            transition: var(--transition);
        }
        .filter-select:focus {
            outline: none;
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(59  130 246 / 0.1);
        }
    </style>
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

    <!-- ==================== HERO ==================== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Our Work Speaks For Itself</h1>
                    <p class="hero-subtitle">Real projects, real results. Explore how we've helped businesses transform through intelligent technology solutions.</p>
                    <div class="hero-actions">
                        <a href="#projects" class="btn btn-primary">View Projects</a>
                        <a href="contact.php" class="btn btn-secondary">Start Your Project</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="../assets/images/dashboard.png" alt="Portfolio" style="width:100%;max-width:500px;border-radius:var(--radius-2xl);box-shadow:var(--shadow-2xl);">
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== PORTFOLIO FILTERS ==================== -->
    <section id="projects" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Featured Projects</h2>
                <p>Award-winning work across industries and technologies.</p>
            </div>

            <div class="filter-group" style="margin-bottom:3rem;">
                <select class="filter-select" id="filter-industry" onchange="filterPortfolio()">
                    <option value="all">All Industries</option>
                    <option value="ecommerce">E-Commerce</option>
                    <option value="saas">SaaS</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="finance">Finance</option>
                    <option value="education">Education</option>
                </select>
                <select class="filter-select" id="filter-service" onchange="filterPortfolio()">
                    <option value="all">All Services</option>
                    <option value="website">Website Development</option>
                    <option value="automation">Automation</option>
                    <option value="app">Mobile App</option>
                    <option value="webapp">Web Application</option>
                    <option value="airtable">Airtable</option>
                </select>
                <select class="filter-select" id="filter-tech" onchange="filterPortfolio()">
                    <option value="all">All Technologies</option>
                    <option value="php">PHP</option>
                    <option value="js">JavaScript</option>
                    <option value="flutter">Flutter</option>
                    <option value="n8n">n8n</option>
                    <option value="zapier">Zapier</option>
                    <option value="airtable">Airtable</option>
                </select>
            </div>

            <!-- ==================== PORTFOLIO GRID ==================== -->
            <div class="portfolio-masonry" id="portfolio-grid">
                <!-- Project 1 -->
                <div class="portfolio-item-masonry" data-industry="ecommerce" data-service="website" data-tech="php js">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">🛒</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">E-Commerce</span>
                            <h3 class="portfolio-title">Luxe Fashion E-Commerce</h3>
                            <p class="portfolio-description">Complete e-commerce platform with custom checkout, inventory management, and 45% conversion increase.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">PHP</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">MySQL</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Stripe</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="portfolio-item-masonry" data-industry="finance" data-service="automation" data-tech="n8n zapier">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">⚡</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Automation</span>
                            <h3 class="portfolio-title">Invoice & Payment Automation</h3>
                            <p class="portfolio-description">Reduced manual invoice processing by 70%. Automated routing, approvals, and payment tracking.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">n8n</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">QuickBooks</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="portfolio-item-masonry" data-industry="healthcare" data-service="app" data-tech="flutter">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">📱</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Mobile App</span>
                            <h3 class="portfolio-title">MedCare Patient Portal</h3>
                            <p class="portfolio-description">Cross-platform Flutter app for patient scheduling, records, and telemedicine with offline sync.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Flutter</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Firebase</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 4 -->
                <div class="portfolio-item-masonry" data-industry="saas" data-service="webapp" data-tech="php js">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">📊</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Web Application</span>
                            <h3 class="portfolio-title">Analytics Dashboard Platform</h3>
                            <p class="portfolio-description">Custom BI platform with real-time KPIs, custom reports, and role-based access for enterprise clients.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">React</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Node.js</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">PostgreSQL</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 5 -->
                <div class="portfolio-item-masonry" data-industry="education" data-service="airtable" data-tech="airtable zapier">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">🏫</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Airtable System</span>
                            <h3 class="portfolio-title">Student Management System</h3>
                            <p class="portfolio-description">Custom Airtable-based CRM for a university—replaced 4 tools, saving $50k annually.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Airtable</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Zapier</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 6 -->
                <div class="portfolio-item-masonry" data-industry="finance" data-service="automation" data-tech="make zapier">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">🔄</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Automation</span>
                            <h3 class="portfolio-title">CRM Integration Hub</h3>
                            <p class="portfolio-description">Unified customer data across 5 tools with real-time sync. Reduced data entry by 85%.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Make.com</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">Salesforce</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 7 -->
                <div class="portfolio-item-masonry" data-industry="ecommerce" data-service="website" data-tech="php js">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">🏪</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">E-Commerce</span>
                            <h3 class="portfolio-title">Multi-Vendor Marketplace</h3>
                            <p class="portfolio-description">Full marketplace platform with vendor dashboards, payments, and admin controls.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">PHP</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">JavaScript</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>

                <!-- Project 8 -->
                <div class="portfolio-item-masonry" data-industry="saas" data-service="webapp" data-tech="php js">
                    <div class="portfolio-item">
                        <div class="portfolio-image"><span style="font-size:4rem;">🗂️</span></div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">Web Application</span>
                            <h3 class="portfolio-title">Project Management Platform</h3>
                            <p class="portfolio-description">Custom PM tool with Gantt charts, time tracking, and team collaboration features.</p>
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:1rem;">
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">PHP</span>
                                <span style="padding:0.25rem 0.6rem;background:var(--slate-100);border-radius:var(--radius-full);font-size:0.75rem;color:var(--slate-600);">React</span>
                            </div>
                            <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA SECTION ==================== -->
    <section class="cta-section section">
        <div class="container">
            <h2>Ready To Build Something Amazing?</h2>
            <p>Let's discuss your project and create something extraordinary together.</p>
            <div class="cta-actions">
                <a href="contact.php" class="btn btn-primary btn-lg">Start Your Project</a>
                <a href="contact.php" class="btn btn-secondary btn-lg">Get In Touch</a>
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
                        <li><a href="services.php">Website Development</a></li>
                        <li><a href="services.php">Automation</a></li>
                        <li><a href="services.php">Mobile Apps</a></li>
                        <li><a href="services.php">n8n, Zapier, Make</a></li>
                        <li><a href="services.php">Airtable Solutions</a></li>
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

    <script>
    function filterPortfolio() {
        const industry = document.getElementById('filter-industry').value;
        const service = document.getElementById('filter-service').value;
        const tech = document.getElementById('filter-tech').value;
        const items = document.querySelectorAll('.portfolio-item-masonry');

        items.forEach(item => {
            const i = item.dataset.industry;
            const s = item.dataset.service;
            const t = item.dataset.tech;

            const matchIndustry = industry === 'all' || i.includes(industry);
            const matchService = service === 'all' || s.includes(service);
            const matchTech = tech === 'all' || t.includes(tech);

            if (matchIndustry && matchService && matchTech) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
    </script>
</body>
</html>
