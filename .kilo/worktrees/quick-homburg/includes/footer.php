<?php
/**
 * Data Tutors - Footer Include
 * Common footer for all pages
 */
?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Data Tutors" style="height: 32px; width: auto;">
                    </div>
                    <p class="footer-text">Master Excel, Data Analysis, and Data Automation with our comprehensive online courses. Start your journey to becoming a data professional today.</p>
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
                    <h4 class="footer-title">Courses</h4>
                    <ul class="footer-links">
                        <li><a href="<?= APP_URL ?>/course/index.php?category=excel">Excel Mastery</a></li>
                        <li><a href="<?= APP_URL ?>/course/index.php?category=data-analysis">Data Analysis</a></li>
                        <li><a href="<?= APP_URL ?>/course/index.php?category=automation">Data Automation</a></li>
                        <li><a href="<?= APP_URL ?>/course/index.php">All Courses</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Company</h4>
                    <ul class="footer-links">
                        <li><a href="<?= APP_URL ?>/about.php">About Us</a></li>
                        <li><a href="<?= APP_URL ?>/contact.php">Contact</a></li>
                        <li><a href="<?= APP_URL ?>/careers.php">Careers</a></li>
                        <li><a href="<?= APP_URL ?>/blog.php">Blog</a></li>
                        <li><a href="<?= APP_URL ?>/affiliates.php">Affiliate Program</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4 class="footer-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="<?= APP_URL ?>/help.php">Help Center</a></li>
                        <li><a href="<?= APP_URL ?>/forum/index.php">Community Forum</a></li>
                        <li><a href="<?= APP_URL ?>/forum/ask.php">Ask a Question</a></li>
                        <li><a href="<?= APP_URL ?>/faq.php">FAQs</a></li>
                        <li><a href="<?= APP_URL ?>/privacy.php">Privacy Policy</a></li>
                        <li><a href="<?= APP_URL ?>/terms.php">Terms of Service</a></li>
                        <li><a href="<?= APP_URL ?>/cookies.php">Cookie Policy</a></li>
                        <li><a href="<?= APP_URL ?>/pricing.php">Pricing Plans</a></li>
                    </ul>
                </div>
                                                
                <div class="footer-col">
                    <h4 class="footer-title">Newsletter</h4>
                    <p class="footer-text">Subscribe to get updates on new courses and special offers.</p>
                    <form class="newsletter-form" action="<?= APP_URL ?>/subscribe.php" method="POST">
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
                
            </div>
            
            <div class="footer-bottom">
                 <p class="footer-copyright">&copy; <?= date('Y') ?> Data <a href="<?= APP_URL ?>/admin/index.php" style="color: inherit; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Tutors</a>. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="<?= APP_URL ?>/privacy.php">Privacy Policy</a>
                    <a href="<?= APP_URL ?>/terms.php">Terms of Service</a>
                    <a href="<?= APP_URL ?>/cookies.php">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?= APP_URL ?>/assets/js/app.js"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
        <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
