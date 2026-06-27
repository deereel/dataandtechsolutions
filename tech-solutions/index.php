<?php
/* --------------------------------------------------------------
   Tech Solutions – Home Page
   Uses local assets under tech-solutions/assets/
   -------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Solutions | Data Tutors & Tech Solutions</title>
    <meta name="description" content="Premium technology services – custom websites, automation, apps, and digital transformation.">
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
        <nav class="nav-menu">
            <a href="index.php" class="nav-link active">Home</a>
            <a href="about.php" class="nav-link">About</a>
            <a href="services.php" class="nav-link">Services</a>
            <a href="portfolio.php" class="nav-link">Portfolio</a>
            <a href="blog.php" class="nav-link">Blog</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="contact.php" class="btn btn-primary nav-cta">Get Free Consultation</a>
        </nav>
    </nav>

    <!-- ==================== HERO ==================== -->
    <section class="hero">
        <div class="hero-bg-mesh"></div>
        <div class="hero-grid"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Transform Your Business With Smart Websites, Apps & Automation</h1>
                <p class="hero-subtitle">We help businesses automate workflows, build powerful websites, develop custom applications, and streamline operations using modern technologies.</p>
                <div class="hero-actions">
                    <a href="contact.php" class="btn btn-primary">Get Free Consultation</a>
                    <a href="portfolio.php" class="btn btn-secondary">View Our Work</a>
                </div>
                <div class="hero-trust-badges">
                    <span class="trust-badge"> n8n</span>
                    <span class="trust-badge"> Zapier</span>
                    <span class="trust-badge"> Make</span>
                    <span class="trust-badge"> Airtable</span>
                    <span class="trust-badge"> Web Apps</span>
                    <span class="trust-badge"> Mobile Apps</span>
                </div>
            </div>
            <div class="hero-visual">
                <div class="floating-cards">
                    <div class="float-card card-1"> <img src="../assets/images/web.png" alt="" style="width:28px;height:28px;"> Website Development</div>
                    <div class="float-card card-2"> <img src="../assets/images/automation.png" alt="" style="width:28px;height:28px;"> Workflow Automation</div>
                    <div class="float-card card-3"> <img src="../assets/images/mobile.png" alt="" style="width:28px;height:28px;"> Mobile Apps</div>
                    <div class="float-card card-4"> <img src="../assets/images/dashboard.png" alt="" style="width:28px;height:28px;"> Business Dashboards</div>
                    <div class="float-card card-5"> <img src="../assets/images/n8n.svg" alt="" style="width:28px;height:28px;"> AI Integration</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TRUST INDICATORS ==================== -->
    <section id="trust" class="section stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">1,200+</span>
                    <span class="stat-label">Projects Completed</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Businesses Served</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">8</span>
                    <span class="stat-label">Years Experience</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">97%</span>
                    <span class="stat-label">Client Satisfaction</span>
                </div>
            </div>
            <h2 class="text-center" style="margin-top:2rem;">Trusted By Growing Businesses</h2>
        </div>
    </section>

    <!-- ==================== ABOUT COMPANY SUMMARY ==================== -->
    <section id="about" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Who We Are</h2>
                <p>Data Tutors & Tech Solutions is a premium technology agency building intelligent digital experiences for modern businesses.</p>
            </div>
            <div class="about-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
                <div>
                    <img src="../assets/images/company-team.jpg" alt="Our Team" style="width:100%;border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);">
                </div>
                <div>
                    <h3 style="margin-bottom:1rem;">What We Do</h3>
                    <p style="margin-bottom:1.5rem;">We specialize in custom website development, workflow automation, mobile applications, and end-to-end digital transformation solutions that help businesses scale efficiently.</p>
                    <ul style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:2rem;">
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="width:24px;height:24px;background:linear-gradient(135deg,var(--blue-500),var(--purple-600));border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;flex-shrink:0;">✓</span> High-converting websites & e-commerce platforms</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="width:24px;height:24px;background:linear-gradient(135deg,var(--blue-500),var(--purple-600));border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;flex-shrink:0;">✓</span> Automated workflows using n8n, Zapier & Make</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="width:24px;height:24px;background:linear-gradient(135deg,var(--blue-500),var(--purple-600));border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;flex-shrink:0;">✓</span> Bespoke mobile and web applications</li>
                        <li style="display:flex;align-items:center;gap:0.75rem;"><span style="width:24px;height:24px;background:linear-gradient(135deg,var(--blue-500),var(--purple-600));border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:0.85rem;flex-shrink:0;">✓</span> Airtable databases, CRM & business systems</li>
                    </ul>
                    <a href="about.php" class="btn btn-primary">Learn More About Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== SERVICES ==================== -->
    <section id="services" class="section">
        <div class="container">
            <div class="section-header">
                <h2>Our Services</h2>
                <p>Comprehensive technology solutions designed to automate, scale, and transform your business.</p>
            </div>
            <div class="services-grid">
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/web.png" alt="" style="width:32px;height:32px;"></div>
                    <h3>Custom Website Development</h3>
                    <p>From landing pages to full-scale e-commerce, we design solutions that turn visitors into customers.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/automation.png" alt="" style="width:32px;height:32px;"></div>
                    <h3>Business Automation</h3>
                    <p>Eliminate manual work with intelligent workflows built on n8n, Zapier, and Make.com.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/n8n.svg" alt="" style="width:32px;height:32px;"></div>
                    <h3>n8n Automation</h3>
                    <p>Custom automation pipelines connecting APIs, databases and SaaS tools for seamless operations.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/zapier.svg" alt="" style="width:32px;height:32px;"></div>
                    <h3>Zapier Automation</h3>
                    <p>Quick, low-code automations for everyday business tasks with hundreds of integrations.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/make.svg" alt="" style="width:32px;height:32px;"></div>
                    <h3>Make.com Automation</h3>
                    <p>Powerful visual automations for complex multi-step business processes.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/airtable.svg" alt="" style="width:32px;height:32px;"></div>
                    <h3>Airtable Solutions</h3>
                    <p>Custom databases and CRM systems that scale with your business needs.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/mobile.png" alt="" style="width:32px;height:32px;"></div>
                    <h3>Mobile App Development</h3>
                    <p>Native iOS/Android and cross-platform apps built for performance and user experience.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/webapp.png" alt="" style="width:32px;height:32px;"></div>
                    <h3>Web Applications</h3>
                    <p>Full-stack web portals, admin dashboards, and customer-facing portals.</p>
                    <a href="services.php" class="btn btn-sm btn-primary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== WHY CHOOSE US ==================== -->
    <section id="why" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose Tech Solutions?</h2>
                <p>We combine technical expertise with business acumen to deliver measurable results.</p>
            </div>
            <div class="why-grid">
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/results.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Results Driven</h3>
                    <p>Every solution is designed to deliver tangible business outcomes and measurable ROI.</p>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/experts.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Automation Experts</h3>
                    <p>Deep expertise in n8n, Zapier, Make.com, and Airtable integration.</p>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/custom.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Custom Solutions</h3>
                    <p>Tailored systems built around your unique business processes and goals.</p>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/fast.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Fast Delivery</h3>
                    <p>Agile methodology ensures rapid deployment without compromising quality.</p>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/support.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Dedicated Support</h3>
                    <p>24/7 maintenance and dedicated support teams for your peace of mind.</p>
                </div>
                <div class="card">
                    <div class="card-icon-wrap"><img src="../assets/images/scalable.png" alt="" style="width:28px;height:28px;"></div>
                    <h3>Scalable Systems</h3>
                    <p>Architecture designed to grow with your business from startup to enterprise.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== OUR PROCESS ==================== -->
    <section id="process" class="section">
        <div class="container">
            <div class="section-header">
                <h2>Our Process</h2>
                <p>A proven methodology that ensures every project is delivered on time and exceeds expectations.</p>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h4 class="step-title">Discovery</h4>
                    <p class="step-description">Understanding your business, goals, and challenges.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h4 class="step-title">Strategy</h4>
                    <p class="step-description">Creating a roadmap tailored to your objectives.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h4 class="step-title">Design</h4>
                    <p class="step-description">Crafting intuitive, premium user experiences.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h4 class="step-title">Development</h4>
                    <p class="step-description">Building robust, scalable technology solutions.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">5</div>
                    <h4 class="step-title">Testing</h4>
                    <p class="step-description">Rigorous QA to ensure reliability and performance.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">6</div>
                    <h4 class="step-title">Deployment</h4>
                    <p class="step-description">Seamless launch with zero disruption to your operations.</p>
                </div>
                <div class="process-step">
                    <div class="step-number">7</div>
                    <h4 class="step-title">Support</h4>
                    <p class="step-description">Ongoing maintenance and continuous optimization.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FEATURED PROJECTS ==================== -->
    <section id="portfolio" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Featured Projects</h2>
                <p>Real results delivered for businesses across industries.</p>
            </div>
            <div class="portfolio-filters">
                <button class="btn btn-outline filter-btn active" data-filter="all">All</button>
                <button class="btn btn-outline filter-btn" data-filter="website">Websites</button>
                <button class="btn btn-outline filter-btn" data-filter="automation">Automation</button>
                <button class="btn btn-outline filter-btn" data-filter="app">Apps</button>
            </div>
            <div class="portfolio-grid" id="project-grid">
                <div class="portfolio-item" data-category="website">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">🛒</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">E-Commerce</span>
                        <h3 class="portfolio-title">E-Commerce Platform</h3>
                        <p class="portfolio-description">Boosted sales 45% with a new checkout flow and personalized recommendations.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
                <div class="portfolio-item" data-category="automation">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">⚡</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">Automation</span>
                        <h3 class="portfolio-title">Invoice Automation</h3>
                        <p class="portfolio-description">Reduced manual invoice processing time by 70% with auto-routing.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
                <div class="portfolio-item" data-category="app">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">📱</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">Mobile App</span>
                        <h3 class="portfolio-title">Mobile CRM</h3>
                        <p class="portfolio-description">Field staff now update records in real time with offline sync.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
                <div class="portfolio-item" data-category="website">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">🏢</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">Corporate</span>
                        <h3 class="portfolio-title">Corporate Website</h3>
                        <p class="portfolio-description">Complete rebrand and digital presence for a Fortune 500 client.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
                <div class="portfolio-item" data-category="automation">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">🔄</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">Workflow</span>
                        <h3 class="portfolio-title">CRM Integration Hub</h3>
                        <p class="portfolio-description">Unified customer data across 5 tools with real-time sync.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
                <div class="portfolio-item" data-category="app">
                    <div class="portfolio-thumb"><span style="font-size:3rem;">📊</span></div>
                    <div class="portfolio-body">
                        <span class="portfolio-tag">Dashboard</span>
                        <h3 class="portfolio-title">Analytics Dashboard</h3>
                        <p class="portfolio-description">Custom business intelligence platform with live KPIs.</p>
                        <a href="case-study.php" class="btn btn-sm btn-primary">View Case Study</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== AUTOMATION SHOWCASE ==================== -->
    <section id="automation-showcase" class="section">
        <div class="container">
            <div class="section-header">
                <h2>Automation Showcase</h2>
                <p>We build powerful automations with the industry's leading platforms.</p>
            </div>
            <div class="services-grid">
                <div class="card" style="text-align:center;">
                    <div class="card-icon-wrap" style="margin:0 auto 1.25rem;"><img src="../assets/images/n8n.svg" alt="n8n" style="width:32px;height:32px;"></div>
                    <h3>n8n Workflows</h3>
                    <p>Complex, multi-step automations connecting APIs, databases, and SaaS tools.</p>
                </div>
                <div class="card" style="text-align:center;">
                    <div class="card-icon-wrap" style="margin:0 auto 1.25rem;"><img src="../assets/images/zapier.svg" alt="Zapier" style="width:32px;height:32px;"></div>
                    <h3>Zapier Automations</h3>
                    <p>Quick integrations for everyday business tasks across hundreds of apps.</p>
                </div>
                <div class="card" style="text-align:center;">
                    <div class="card-icon-wrap" style="margin:0 auto 1.25rem;"><img src="../assets/images/make.svg" alt="Make" style="width:32px;height:32px;"></div>
                    <h3>Make.com Integrations</h3>
                    <p>Advanced visual workflows for complex multi-step business processes.</p>
                </div>
                <div class="card" style="text-align:center;">
                    <div class="card-icon-wrap" style="margin:0 auto 1.25rem;"><img src="../assets/images/airtable.svg" alt="Airtable" style="width:32px;height:32px;"></div>
                    <h3>Airtable Databases</h3>
                    <p>Custom CRM, project management, and internal business systems.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TESTIMONIALS ==================== -->
    <section id="testimonials" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>What Our Clients Say</h2>
                <p>Trusted by businesses worldwide for exceptional results.</p>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">Tech Solutions transformed our clunky spreadsheet-driven process into a sleek automated pipeline that saves us hundreds of hours each month.</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">AR</div>
                        <div class="testimonial-meta">
                            <strong>Anna Rivera</strong>
                            <span>COO, BrightGear</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonials-slider" style="margin-top:2rem;">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">Their custom CRM integration helped us scale from 50 to 500 active users without a single crash. The automation alone saved us over $200k in operational costs.</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">MP</div>
                        <div class="testimonial-meta">
                            <strong>Mike Patel</strong>
                            <span>Founder, ScaleUp Co.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonials-slider" style="margin-top:2rem;">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">The team built us a custom Airtable system that replaced 4 separate tools. Our project delivery time dropped by 60% within the first month.</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">SJ</div>
                        <div class="testimonial-meta">
                            <strong>Sarah Johnson</strong>
                            <span>Director, NexGen Logistics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== BUSINESS IMPACT STATISTICS ==================== -->
    <section id="stats" class="section stats">
        <div class="container">
            <div class="section-header">
                <h2>Our Impact In Numbers</h2>
                <p>Measurable results that demonstrate our commitment to excellence.</p>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">4,500+</span>
                    <span class="stat-label">Projects Delivered</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">2,000,000+</span>
                    <span class="stat-label">Automation Hours Saved</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">350+</span>
                    <span class="stat-label">Apps Built</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">120+</span>
                    <span class="stat-label">Websites Launched</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FAQ ==================== -->
    <section id="faq" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Clear answers to help you make informed decisions.</p>
            </div>
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question">What is your typical project timeline?</button>
                    <div class="faq-answer">Most projects are delivered within 6–10 weeks, depending on scope and complexity. Automation projects typically launch in 2–4 weeks.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Do you offer post-launch support?</button>
                    <div class="faq-answer">Yes – we provide flexible 24/7 maintenance plans, rapid issue resolution, and continuous optimization services.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">What technologies do you specialize in?</button>
                    <div class="faq-answer">We specialize in PHP, JavaScript, Flutter, MySQL, n8n, Zapier, Make.com, Airtable, and modern cloud infrastructure.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">How much does a custom website cost?</button>
                    <div class="faq-answer">Projects typically range from $3,000 to $25,000+ depending on features, complexity, and integrations required.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Can you automate our existing business processes?</button>
                    <div class="faq-answer">Absolutely. We analyze your current workflows and design custom automation solutions using n8n, Zapier, and Make.com.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Do you build mobile apps for iOS and Android?</button>
                    <div class="faq-answer">Yes, we develop native iOS and Android apps, as well as cross-platform solutions using Flutter for broader reach.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">What makes your automation solutions different?</button>
                    <div class="faq-answer">We build enterprise-grade automations with robust error handling, monitoring, and scalable architecture designed for long-term growth.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">How do you handle project communication?</button>
                    <div class="faq-answer">We assign a dedicated project manager, provide weekly updates, and use collaborative tools for transparent progress tracking.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">Can you integrate with our existing tools?</button>
                    <div class="faq-answer">Yes, we integrate with 1000+ platforms including CRMs, ERPs, marketing tools, payment gateways, and custom APIs.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">What is your payment structure?</button>
                    <div class="faq-answer">We typically work with a 50% upfront deposit and 50% on completion. Larger projects may have milestone-based payments.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FINAL CALL TO ACTION ==================== -->
    <section id="cta" class="cta-section section">
        <div class="container">
            <h2 class="text-center">Ready To Digitally Transform Your Business?</h2>
            <p class="text-center" style="max-width:600px;margin:1rem auto 2rem;color:var(--slate-300);">Let's build intelligent systems that save time, cut costs, and accelerate growth.</p>
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
                    <p class="footer-intro">Premium technology services for modern businesses. We build intelligent websites, apps, and automation systems that drive growth.</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="about.php">About</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="portfolio.php">Portfolio</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact Us</h4>
                    <ul class="footer-contact">
                        <li>Phone: <strong>(555) 123-4567</strong></li>
                        <li>Email: <strong>info@datatutorstech.com</strong></li>
                        <li>Address: <strong>123 Tech Innovation Drive, Suite 100, Silicon Valley, CA</strong></li>
                    </ul>
                </div>
                <div>
                    <h4>Newsletter</h4>
                    <p style="font-size:0.95rem;color:var(--slate-400);margin-bottom:1rem;">Get the latest insights on tech and automation.</p>
                    <form style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <input type="email" placeholder="Your email" style="padding:0.6rem 1rem;border-radius:var(--radius-full);border:1px solid var(--slate-700);background:var(--slate-800);color:white;flex:1;min-width:180px;font-family:var(--font-sans);">
                        <button type="submit" class="btn btn-sm btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y'); ?> Tech Solutions. All rights reserved.</span>
                <div class="footer-social">
                    <a href="#" aria-label="LinkedIn" class="social-link" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);display:inline-flex;align-items:center;justify-content:center;color:white;text-decoration:none;transition:var(--transition);">in</a>
                    <a href="#" aria-label="Twitter" class="social-link" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);display:inline-flex;align-items:center;justify-content:center;color:white;text-decoration:none;transition:var(--transition);">𝕏</a>
                    <a href="#" aria-label="Facebook" class="social-link" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);display:inline-flex;align-items:center;justify-content:center;color:white;text-decoration:none;transition:var(--transition);">f</a>
                    <a href="#" aria-label="Instagram" class="social-link" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.06);display:inline-flex;align-items:center;justify-content:center;color:white;text-decoration:none;transition:var(--transition);">📷</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>