<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Solutions | Data Tutors & Tech Solutions</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <style>
        /* Additional styles for Tech Solutions */
        :root {
            --tech-primary: #1e3a8a; /* Dark blue */
            --tech-primary-light: #3b82f6;
            --tech-accent: #06b6d4;
            --tech-dark: #0f172a;
            --tech-light: #f8fafc;
        }
        .tech-primary-bg {
            background-color: var(--tech-primary);
            color: white;
        }
        .tech-primary {
            color: var(--tech-primary);
        }
        .tech-accent {
            color: var(--tech-accent);
        }
        .tech-section {
            padding: 6rem 0;
        }
        .hero-tech {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--tech-light) 0%, var(--white) 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-tech::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 150%;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-content-tech {
            max-width: 560px;
        }
        .hero-title-tech {
            font-size: 3.25rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: var(--tech-dark);
        }
        .hero-title-tech span {
            color: var(--tech-primary);
        }
        .hero-text-tech {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
            max-width: 500px;
        }
        .hero-actions-tech {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        .tech-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(30, 58, 138, 0.1);
            color: var(--tech-primary);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        .tech-badge span {
            font-size: 0.875rem;
            background: var(--tech-primary);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header tech-header">
        <div class="container header-inner">
            <a href="#" class="logo tech-primary">
                <img src="./assets/images/logo.png" alt="Data Tutors & Tech Solutions" class="logo-image" style="height: 40px; width: auto;">
                <span class="ml-2">Tech Solutions</span>
            </a>
            
            <nav class="nav">
                <a href="#home" class="nav-link">Home</a>
                <a href="#about" class="nav-link">About</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#portfolio" class="nav-link">Portfolio</a>
                <a href="#blog" class="nav-link">Blog</a>
                <a href="#contact" class="nav-link">Contact</a>
                
                <div class="nav-actions">
                    <a href="#contact" class="btn btn-primary btn-lg">Get Free Consultation</a>
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
        <section id="home" class="hero-tech">
            <div class="container">
                <div class="hero-inner">
                    <div class="hero-content-tech">
                        <div class="tech-badge">
                            <span>Tech Solutions</span>
                        </div>
                        <h1 class="hero-title-tech">
                            Transform Your Business With Smart <span>Websites, Apps & Automation</span>
                        </h1>
                        <p class="hero-text-tech">
                            We help businesses automate workflows, build powerful websites, develop custom applications, and streamline operations using modern technologies.
                        </p>
                        <div class="hero-actions-tech">
                            <a href="#contact" class="btn btn-primary btn-lg">Get Free Consultation</a>
                            <a href="#portfolio" class="btn btn-outline btn-lg">View Our Work</a>
                        </div>
                        
                        <!-- Tech Badges -->
                        <div class="tech-badges" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <span class="tech-badge"><span>n8n</span></span>
                            <span class="tech-badge"><span>Zapier</span></span>
                            <span class="tech-badge"><span>Make</span></span>
                            <span class="tech-badge"><span>Airtable</span></span>
                            <span class="tech-badge"><span>Web Apps</span></span>
                            <span class="tech-badge"><span>Mobile Apps</span></span>
                        </div>
                    </div>
                    
                    <div class="hero-image-tech">
                        <!-- Placeholder for tech illustration -->
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
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="./assets/images/logo.png" alt="Data Tutors & Tech Solutions" style="height: 32px; width: auto;">
                    </div>
                    <p class="footer-text">Transforming businesses with custom technology solutions.</p>
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                        </a>
                        <a href="#" class="social-link" aria-label="YouTube">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"></polygon></svg>
                        </a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Tech Solutions</h4>
                    <ul class="footer-links">
                        <li><a href="#services">Services</a></li>
                        <li><a href="#portfolio">Portfolio</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Resources</h4>
                    <ul class="footer-links">
                        <li><a href="#">Case Studies</a></li>
                        <li><a href="#">Whitepapers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Contact Information</h4>
                    <ul class="footer-links">
                        <li>Phone: (555) 123-4567</li>
                        <li>Email: info@datatutorstech.com</li>
                        <li>Address: 123 Tech Innovation Drive, Suite 100, Silicon Valley, CA</li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Newsletter</h4>
                    <p class="footer-text">Subscribe to get updates on our latest tech solutions.</p>
                    <form class="newsletter-form" action="#" method="POST">
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; 2026 Data Tutors & Tech Solutions. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="./assets/js/app.js"></script>
</body>
</html>