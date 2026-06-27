<?php
/* --------------------------------------------------------------
   Tech Solutions – About Page
   -------------------------------------------------------------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Tech Solutions | Data Tutors & Tech Solutions</title>
    <meta name="description" content="Learn about Data Tutors & Tech Solutions - building premium digital experiences and intelligent automation systems.">
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
            <a href="about.php" class="nav-link active">About</a>
            <a href="services.php" class="nav-link">Services</a>
            <a href="portfolio.php" class="nav-link">Portfolio</a>
            <a href="blog.php" class="nav-link">Blog</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <a href="contact.php" class="btn btn-sm btn-primary nav-cta">Get Free Consultation</a>
        </nav>
    </nav>

    <!-- ==================== HERO SECTION ==================== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Building Technology That Helps Businesses Grow</h1>
                    <p class="hero-subtitle">We are a team of passionate technologists dedicated to transforming businesses through innovative digital solutions, intelligent automation, and cutting-edge software development.</p>
                    <div class="hero-buttons">
                        <a href="contact.php" class="btn btn-primary">Book Consultation</a>
                        <a href="#services" class="btn btn-secondary">Our Services</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="../assets/images/company-team.jpg" alt="Our Team" style="width:100%;max-width:600px;border-radius:var(--radius-2xl);box-shadow:var(--shadow-2xl);">
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== COMPANY STORY ==================== -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Our Story</h2>
                <p>Founded with a vision to bridge the gap between complex technology and business needs.</p>
            </div>
            <div class="about-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
                <div>
                    <h3 style="margin-bottom:1.5rem;font-size:1.5rem;">From Data Analytics to Full-Scale Technology Solutions</h3>
                    <p style="margin-bottom:1.5rem;font-size:1.05rem;">Data Tutors & Tech Solutions was born from a simple observation: businesses were struggling to find technology partners who could both understand their data and build production-ready solutions.</p>
                    <p style="margin-bottom:1.5rem;font-size:1.05rem;">We started as a data analytics consultancy, helping businesses make sense of their numbers. But our clients kept asking us to build the tools to act on those insights. So we expanded—into web development, automation, mobile apps, and custom business systems.</p>
                    <p style="font-size:1.05rem;">Today, we're a full-service technology partner trusted by hundreds of businesses to deliver solutions that are as intelligent as they are beautiful.</p>
                </div>
                <div style="display:flex;flex-direction:column;gap:1.5rem;">
                    <div class="card">
                        <h3 style="margin-bottom:0.75rem;">Why We Exist</h3>
                        <p style="font-size:0.95rem;">Most technology agencies focus on either design or engineering. We do both—because the best digital experiences come from the intersection of beautiful design and robust engineering.</p>
                    </div>
                    <div class="card">
                        <h3 style="margin-bottom:0.75rem;">What We Solve</h3>
                        <p style="font-size:0.95rem;">Manual processes that drain time, websites that don't convert, data trapped in spreadsheets, and systems that can't scale. We build solutions that solve these problems elegantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MISSION & VISION ==================== -->
    <section class="section">
        <div class="container">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;">
                <div class="card" style="padding:3rem;background:linear-gradient(135deg, rgb(59 130 246 / 0.03), rgb(139 92 246 / 0.03));">
                    <div style="width:56px;height:56px;border-radius:var(--radius-lg);background:linear-gradient(135deg,var(--blue-500),var(--purple-600));display:flex;align-items:center;justify-content:center;color:white;font-size:1.5rem;margin-bottom:1.5rem;">🎯</div>
                    <h3>Our Mission</h3>
                    <p style="font-size:1.05rem;">To empower businesses of all sizes to leverage technology, automation, and digital transformation to achieve unprecedented efficiency, growth, and competitive advantage.</p>
                </div>
                <div class="card" style="padding:3rem;background:linear-gradient(135deg, rgb(6 182 212 / 0.03), rgb(139 92 246 / 0.03));">
                    <div style="width:56px;height:56px;border-radius:var(--radius-lg);background:linear-gradient(135deg,var(--cyan-500),var(--purple-600));display:flex;align-items:center;justify-content:center;color:white;font-size:1.5rem;margin-bottom:1.5rem;">🔭</div>
                    <h3>Our Vision</h3>
                    <p style="font-size:1.05rem;">To be the world's most trusted technology partner for businesses seeking to transform operations through intelligent automation, elegant software, and data-driven decision making.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CORE VALUES ==================== -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Our Core Values</h2>
                <p>The principles that guide every decision we make and every solution we build.</p>
            </div>
            <div class="values-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">💡</div>
                    <h3 style="margin-bottom:0.75rem;">Innovation</h3>
                    <p style="font-size:0.9rem;">We push boundaries and explore new technologies to deliver cutting-edge solutions.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">🤝</div>
                    <h3 style="margin-bottom:0.75rem;">Integrity</h3>
                    <p style="font-size:0.9rem;">Transparent communication and honest partnerships form the foundation of our work.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">⭐</div>
                    <h3 style="margin-bottom:0.75rem;">Excellence</h3>
                    <p style="font-size:0.9rem;">We hold ourselves to the highest standards in every line of code and every design decision.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">🚀</div>
                    <h3 style="margin-bottom:0.75rem;">Customer Success</h3>
                    <p style="font-size:0.9rem;">Your success is our success. We measure our worth by the impact we create.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">📚</div>
                    <h3 style="margin-bottom:0.75rem;">Continuous Learning</h3>
                    <p style="font-size:0.9rem;">Technology evolves daily, and so do we. We are perpetual students of our craft.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">⚡</div>
                    <h3 style="margin-bottom:0.75rem;">Efficiency</h3>
                    <p style="font-size:0.9rem;">We believe in smart solutions over hard work—automating what can be automated.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== WHY BUSINESSES TRUST US ==================== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Why Businesses Trust Us</h2>
                <p>Deep expertise across the technologies and platforms that power modern business.</p>
            </div>
            <div class="why-grid" style="grid-template-columns:repeat(3,1fr);gap:1.75rem;">
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">🌐</div>
                    <h3 style="margin-bottom:0.75rem;">Web Development Mastery</h3>
                    <p style="font-size:0.9rem;">From corporate sites to complex web apps, we architect solutions that perform and convert.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">⚡</div>
                    <h3 style="margin-bottom:0.75rem;">Automation Expertise</h3>
                    <p style="font-size:0.9rem;">Certified experts in n8n, Zapier, and Make.com who build enterprise-grade automation pipelines.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">📱</div>
                    <h3 style="margin-bottom:0.75rem;">App Development Excellence</h3>
                    <p style="font-size:0.9rem;">Native and cross-platform mobile apps that users love and businesses depend on.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">🔗</div>
                    <h3 style="margin-bottom:0.75rem;">System Integration</h3>
                    <p style="font-size:0.9rem;">Seamlessly connecting your tools, databases, and workflows into cohesive systems.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">📊</div>
                    <h3 style="margin-bottom:0.75rem;">Data-Driven Approach</h3>
                    <p style="font-size:0.9rem;">Every decision backed by analytics, every optimization measured for real business impact.</p>
                </div>
                <div class="card" style="text-align:center;padding:2.5rem;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,rgb(59 130 246 / 0.1),rgb(139 92 246 / 0.1));display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:1.5rem;">🛡️</div>
                    <h3 style="margin-bottom:0.75rem;">Enterprise Reliability</h3>
                    <p style="font-size:0.9rem;">Built for scale, security, and long-term stability—tested under real business pressure.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MEET THE TEAM ==================== -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Meet The Leadership Team</h2>
                <p>The people behind the innovation—driven by passion, guided by expertise.</p>
            </div>
            <div class="team-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:2rem;">
                <div class="team-card">
                    <div class="team-avatar">JM</div>
                    <h4>James Mitchell</h4>
                    <p class="role">Founder & CEO</p>
                    <p>Former tech lead at Fortune 500 companies with 15+ years of experience in software architecture and digital transformation.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">SC</div>
                    <h4>Sarah Chen</h4>
                    <p class="role">Head of Automation</p>
                    <p>Certified n8n and Zapier expert who has built 500+ automations saving clients millions in operational costs.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">DR</div>
                    <h4>David Rodriguez</h4>
                    <p class="role">Lead Developer</p>
                    <p>Full-stack specialist with expertise in modern web technologies, cloud architecture, and scalable application design.</p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">EP</div>
                    <h4>Emily Park</h4>
                    <p class="role">Design Director</p>
                    <p>UX/UI specialist crafting intuitive digital experiences that delight users and drive measurable business results.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TECHNOLOGIES ==================== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Technologies We Work With</h2>
                <p>Proficiency across the full modern tech stack—from frontend to automation platforms.</p>
            </div>
            <div class="tech-stack" style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;max-width:800px;margin:0 auto;">
                <span class="tech-pill">HTML5</span>
                <span class="tech-pill">CSS3</span>
                <span class="tech-pill">JavaScript</span>
                <span class="tech-pill">PHP</span>
                <span class="tech-pill">MySQL</span>
                <span class="tech-pill">React</span>
                <span class="tech-pill">Node.js</span>
                <span class="tech-pill">Flutter</span>
                <span class="tech-pill">n8n</span>
                <span class="tech-pill">Zapier</span>
                <span class="tech-pill">Make.com</span>
                <span class="tech-pill">Airtable</span>
                <span class="tech-pill">Google Workspace</span>
                <span class="tech-pill">AWS</span>
                <span class="tech-pill">Docker</span>
                <span class="tech-pill">Git</span>
            </div>
        </div>
    </section>

    <!-- ==================== ACHIEVEMENTS MILESTONES ==================== -->
    <section class="section section-alt">
        <div class="container">
            <div class="about-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start;">
                <div>
                    <h2 style="margin-bottom:1.5rem;">Our Journey</h2>
                    <p style="margin-bottom:2rem;color:var(--slate-600);">From a small consultancy to a trusted technology partner for businesses worldwide.</p>
                    <div class="milestone-list">
                        <div class="milestone-item">
                            <span class="milestone-year">2018</span>
                            <h4>Founded</h4>
                            <p style="font-size:0.9rem;">Started as a data analytics consultancy serving local businesses.</p>
                        </div>
                        <div class="milestone-item">
                            <span class="milestone-year">2019</span>
                            <h4>Expanded Into Development</h4>
                            <p style="font-size:0.9rem;">Launched web development services after repeated client requests.</p>
                        </div>
                        <div class="milestone-item">
                            <span class="milestone-year">2020</span>
                            <h4>Automation Division</h4>
                            <p style="font-size:0.9rem;">Built dedicated automation practice with n8n, Zapier, and Make.com.</p>
                        </div>
                        <div class="milestone-item">
                            <span class="milestone-year">2022</span>
                            <h4>500+ Projects Delivered</h4>
                            <p style="font-size:0.9rem;">Milestone achievement serving clients across 12 countries.</p>
                        </div>
                        <div class="milestone-item">
                            <span class="milestone-year">2024</span>
                            <h4>Global Reach</h4>
                            <p style="font-size:0.9rem;">Now serving 500+ businesses with 97% client satisfaction rate.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card" style="padding:2.5rem;margin-bottom:1.5rem;">
                        <h3 style="margin-bottom:1rem;">Awards & Recognition</h3>
                        <ul style="display:flex;flex-direction:column;gap:0.75rem;">
                            <li style="display:flex;align-items:center;gap:0.75rem;">
                                <span style="color:#fbbf24;font-size:1.25rem;">★</span>
                                <span><strong>Top Automation Agency 2024</strong> – Make.com Partner Awards</span>
                            </li>
                            <li style="display:flex;align-items:center;gap:0.75rem;">
                                <span style="color:#fbbf24;font-size:1.25rem;">★</span>
                                <span><strong>Best Tech Startup</strong> – Silicon Valley Business Journal</span>
                            </li>
                            <li style="display:flex;align-items:center;gap:0.75rem;">
                                <span style="color:#fbbf24;font-size:1.25rem;">★</span>
                                <span><strong>Top Web Developer</strong> – Clutch.co 2023</span>
                            </li>
                            <li style="display:flex;align-items:center;gap:0.75rem;">
                                <span style="color:#fbbf24;font-size:1.25rem;">★</span>
                                <span><strong>Innovation Excellence</strong> – Tech Innovation Awards</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA SECTION ==================== -->
    <section class="cta-section section">
        <div class="container">
            <h2>Ready To Work With Us?</h2>
            <p>Let's build something extraordinary together. Start your digital transformation today.</p>
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
                    <p class="footer-intro">Premium technology services for modern businesses. Building intelligent websites, apps, and automation systems since 2018.</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="portfolio.php">Portfolio</a></li>
                        <li><a href="blog.php">Blog</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li><a href="services.php">Website Development</a></li>
                        <li><a href="services.php">Business Automation</a></li>
                        <li><a href="services.php">Mobile Apps</a></li>
                        <li><a href="services.php">n8n Automation</a></li>
                        <li><a href="services.php">Airtable Solutions</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact Us</h4>
                    <ul class="footer-contact">
                        <li>📞 (555) 123-4567</li>
                        <li>✉️ info@datatutorstech.com</li>
                        <li>📍 123 Tech Innovation Drive, Suite 100, Silicon Valley, CA</li>
                    </ul>
                    <div class="footer-social">
                        <a href="#" aria-label="LinkedIn">in</a>
                        <a href="#" aria-label="Twitter">𝕏</a>
                        <a href="#" aria-label="Facebook">f</a>
                        <a href="#" aria-label="Instagram">📷</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y'); ?> Tech Solutions. All rights reserved.</span>
                <a href="privacy.php" style="color:var(--slate-500);font-size:0.875rem;">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
