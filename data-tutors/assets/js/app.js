/**
 * Data Tutors - Main JavaScript
 * Handles PWA, UI interactions, and common functionality
 */

// Global app namespace
const App = {
    deferredPrompt: null,
    
    // Initialize the application
    init() {
        this.initPWA();
        this.initMobileMenu();
        this.initScrollEffects();
        this.initForms();
        this.initNotifications();
        this.initProgressTracking();
    },

    // PWA Initialization
    async initPWA() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/pwa/sw.js');
                console.log('Service Worker registered:', registration.scope);
                
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateNotification();
                        }
                    });
                });

                // Handle offline status
                window.addEventListener('offline', () => {
                    this.showNotification('You are offline. Some features may be limited.');
                });

                window.addEventListener('online', () => {
                    this.showNotification('You are back online!');
                });

            } catch (error) {
                console.log('Service Worker registration failed:', error);
            }
        }

        // Register PWA beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            // Show install prompt after a short delay to ensure page is ready
            setTimeout(() => {
                this.showInstallPrompt();
            }, 3000);
        });
    },

    showUpdateNotification() {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info';
        notification.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; max-width: 400px; cursor: pointer;';
        notification.innerHTML = `
            <strong>Update Available!</strong> A new version is available. 
            <button onclick="location.reload()" style="margin-left: 10px; padding: 5px 10px;">Update Now</button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 10000);
    },

    showInstallPrompt() {
        const installBanner = document.getElementById('install-banner');
        if (installBanner && this.deferredPrompt) {
            console.log('Showing PWA install banner');
            installBanner.classList.remove('hidden');
            // Update button to show "Install Now"
            const installBtn = installBanner.querySelector('button:first-of-type');
            if (installBtn) {
                installBtn.textContent = 'Install App';
            }
        } else {
            console.log('Install banner or deferred prompt not available');
        }
    },

    async installPWA() {
        if (!this.deferredPrompt) {
            // Try to show native prompt
            if ('standalone' in window.navigator && window.navigator.standalone) {
                this.showNotification('App is already installed!');
                return;
            }
            this.showNotification('PWA is not ready to install. Please refresh the page.');
            return;
        }
        
        this.deferredPrompt.prompt();
        const { outcome } = await this.deferredPrompt.userChoice;
        console.log('Install outcome:', outcome);
        this.deferredPrompt = null;
        
        const installBanner = document.getElementById('install-banner');
        if (installBanner) {
            installBanner.classList.add('hidden');
        }
        
        if (outcome === 'accepted') {
            this.showNotification('App installed successfully!', 'success');
        }
    },

    // Mobile Menu
    initMobileMenu() {
        const menuBtn = document.querySelector('.mobile-menu-btn');
        const nav = document.querySelector('.nav');
        
        if (menuBtn && nav) {
            menuBtn.addEventListener('click', () => {
                menuBtn.classList.toggle('active');
                nav.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menuBtn.contains(e.target) && !nav.contains(e.target)) {
                    menuBtn.classList.remove('active');
                    nav.classList.remove('active');
                }
            });
        }
    },

    // Scroll Effects
    initScrollEffects() {
        const header = document.querySelector('.header');
        
        if (header) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    },

    // Form Handling
    initForms() {
        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            form.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });
            });
        });

        // AJAX form submission
        document.querySelectorAll('form[data-ajax]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.submitFormAjax(form);
            });
        });
    },

    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    },

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let errorMessage = '';

        // Remove existing error
        this.clearFieldError(field);

        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        // Email validation
        if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }

        // Password validation
        if (type === 'password' && value && value.length < 6) {
            isValid = false;
            errorMessage = 'Password must be at least 6 characters';
        }

        // Confirm password validation
        if (field.dataset.confirm) {
            const confirmField = document.querySelector(`[name="${field.dataset.confirm}"]`);
            if (confirmField && value !== confirmField.value) {
                isValid = false;
                errorMessage = 'Passwords do not match';
            }
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    },

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    showFieldError(field, message) {
        const errorEl = document.createElement('div');
        errorEl.className = 'form-error';
        errorEl.textContent = message;
        field.parentNode.appendChild(errorEl);
        field.classList.add('error');
    },

    clearFieldError(field) {
        const errorEl = field.parentNode.querySelector('.form-error');
        if (errorEl) {
            errorEl.remove();
        }
        field.classList.remove('error');
    },

    async submitFormAjax(form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.textContent : '';

        try {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
            }

            const response = await fetch(form.action, {
                method: form.method,
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message || 'Success!', 'success');
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
                if (data.reset !== false) {
                    form.reset();
                }
            } else {
                this.showNotification(data.message || 'An error occurred', 'error');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    },

    // Notifications
    initNotifications() {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    },

    showNotification(message, type = 'info') {
        // In-app notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    },

    // Progress Tracking
    initProgressTracking() {
        // Mark lesson as complete
        document.querySelectorAll('[data-complete-lesson]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                await this.markLessonComplete(btn.dataset.lessonId);
            });
        });

        // Update progress bar
        this.updateProgressBars();
    },

    async markLessonComplete(lessonId) {
        try {
            const response = await fetch('/api/progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'complete_lesson', lesson_id: lessonId })
            });
            const data = await response.json();
            if (data.success) {
                this.showNotification('Lesson completed!', 'success');
                this.updateProgressBars();
            }
        } catch (error) {
            console.error('Error marking lesson complete:', error);
        }
    },

    updateProgressBars() {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const percentage = bar.dataset.progress;
            if (percentage !== undefined) {
                bar.style.width = `${percentage}%`;
            }
        });
    },

    // Utility functions
    async api(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
        };

        const response = await fetch(endpoint, { ...defaultOptions, ...options });
        return response.json();
    },

    async loadContent(url, container) {
        try {
            const response = await fetch(url);
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error('Error loading content:', error);
            container.innerHTML = '<p class="text-center">Failed to load content</p>';
        }
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
    App.init();
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
`;
document.head.appendChild(style);

// Export for use in other scripts
window.App = App;
