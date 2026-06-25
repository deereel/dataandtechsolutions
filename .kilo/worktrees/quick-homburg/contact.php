<?php
/**
 * Data Tutors - Contact Us Page
 * Contact information and inquiry form
 */

require_once 'config/config.php';

// Page title
define('PAGE_TITLE', 'Contact Us');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Contact Data Tutors for support, inquiries, or feedback. Find our contact information and use our inquiry form.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .contact-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }

        .contact-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .contact-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .contact-content {
            padding: 6rem 0;
            background: white;
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        .contact-info {
            background: var(--gray-50);
            padding: 2rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .contact-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }

        .contact-methods {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .contact-method {
            display: flex;
            gap: 1rem;
            align-items: start;
        }

        .contact-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: white;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-details h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }

        .contact-details p {
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9375rem;
        }

        .contact-form {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .contact-form h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--gray-900);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.9375rem;
            transition: var(--transition);
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-button {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .submit-button:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
        }

        .success-message {
            background: var(--success);
            color: white;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .error-message {
            background: var(--danger);
            color: white;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .map-section {
            padding: 4rem 0;
            background: var(--gray-50);
        }

        .map-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .map-container h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--gray-900);
            text-align: center;
        }

        .map-placeholder {
            width: 100%;
            height: 400px;
            background: var(--gray-200);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            font-size: 1.125rem;
        }

        .faq-section {
            padding: 6rem 0;
            background: white;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .faq-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .faq-header p {
            font-size: 1.125rem;
            color: var(--gray-600);
        }

        .faq-item {
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .faq-question {
            padding: 1.25rem 1.5rem;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .faq-question:hover {
            background: var(--gray-50);
        }

        .faq-question::after {
            content: '+';
            font-size: 1.5rem;
            color: var(--gray-400);
            transition: transform 0.3s ease;
        }

        .faq-question.active::after {
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer.active {
            max-height: 500px;
        }

        .faq-answer-content {
            padding: 0 1.5rem 1.25rem;
            color: var(--gray-600);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .contact-hero {
                padding: 4rem 0;
            }

            .contact-hero h1 {
                font-size: 2rem;
            }

            .contact-hero p {
                font-size: 1rem;
            }

            .contact-content,
            .map-section,
            .faq-section {
                padding: 4rem 0;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .contact-info,
            .contact-form {
                padding: 1.5rem;
            }

            .map-placeholder {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Have questions? We're here to help. Contact our team for support, inquiries, or feedback.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
        <div class="content-container">
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info">
                    <h3>Get In Touch</h3>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="contact-icon">📧</div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p>support@datatutors.com<br>info@datatutors.com</p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="contact-icon">📱</div>
                            <div class="contact-details">
                                <h4>Phone</h4>
                                <p>+1 (555) 123-4567<br>Mon-Fri, 9AM-6PM EST</p>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="contact-icon">🏢</div>
                            <div class="contact-details">
                                <h4>Address</h4>
                                <p>123 Education Street<br>Learning City, LC 12345<br>United States</p>
                            </div>
                        </div>
                    </div>

                    <div style="padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                        <h4>Follow Us</h4>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <a href="#" class="contact-icon" style="width: 40px; height: 40px; background: #3b5998; color: white;">f</a>
                            <a href="#" class="contact-icon" style="width: 40px; height: 40px; background: #1da1f2; color: white;">t</a>
                            <a href="#" class="contact-icon" style="width: 40px; height: 40px; background: #0077b5; color: white;">in</a>
                            <a href="#" class="contact-icon" style="width: 40px; height: 40px; background: #ff0000; color: white;">y</a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    
                    <div id="success-message" class="success-message">
                        Thank you for your message! We'll get back to you soon.
                    </div>

                    <div id="error-message" class="error-message">
                        Something went wrong. Please try again later.
                    </div>

                    <form id="contact-form">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" id="name" name="name" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-textarea" required></textarea>
                        </div>

                        <button type="submit" id="submit-button" class="submit-button">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="map-container">
            <h2>Our Location</h2>
            <div class="map-placeholder">
                Interactive Map Coming Soon
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <div class="faq-header">
                <h2>Frequently Asked Questions</h2>
                <p>Quick answers to common questions about contacting us</p>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    How quickly will I get a response?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>We typically respond to all inquiries within 24-48 hours. For urgent matters, please use our live chat support or call us directly during business hours.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Do you offer live support?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Yes, we offer live chat support during business hours (Mon-Fri, 9AM-6PM EST). You can also schedule a call with one of our education advisors.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What if I need to cancel or refund a purchase?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>We have a 30-day money-back guarantee for all courses. If you're not satisfied for any reason, you can request a full refund within 30 days of purchase. Please contact our support team with your order information.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I schedule a call with an advisor?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Yes, we offer free 15-minute consultation calls with our education advisors. Please use our scheduling tool or contact us to arrange a convenient time.</p>
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    What are your business hours?
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-content">
                        <p>Our business hours are Monday to Friday, 9AM to 6PM Eastern Standard Time (EST). We are closed on major holidays.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Contact form submission
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = document.getElementById('submit-button');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            // Show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';

            // Hide previous messages
            successMessage.classList.remove('show');
            errorMessage.classList.remove('show');

            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                // Show success message
                successMessage.classList.add('show');
                
                // Reset form
                this.reset();
                
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = 'Send Message';
            }, 1500);
        });

        // FAQ toggle functionality
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('active');
                const answer = button.nextElementSibling;
                answer.classList.toggle('active');
            });
        });
    </script>
</body>
</html>