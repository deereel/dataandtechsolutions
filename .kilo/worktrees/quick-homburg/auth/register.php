<?php
/**
 * Data Tutors - Registration Page
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(APP_URL . '/dashboard/index.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    
    // Validate input
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($name) < 2) {
        $error = 'Name must be at least 2 characters.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username can only contain letters, numbers, and underscores.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!$terms) {
        $error = 'You must accept the Terms of Service and Privacy Policy.';
    } else {
        // Check if email already exists
        $existingUser = User::getByEmail($email);
        if ($existingUser) {
            $error = 'An account with this email already exists.';
        } else {
            // Check if username already exists
            $existingUsername = DatabaseConnection::fetch("SELECT id FROM users WHERE username = ?", [$username]);
            if ($existingUsername) {
                $error = 'This username is already taken. Please choose another.';
            } else {
                // Create new user
                $userId = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'student',
                    'status' => 'active',
                    'email_verified' => 0
                ]);
                
                if ($userId) {
                    // Log activity
                    logActivity($userId, 'register', 'users', $userId);
                    
                    // Auto-login after registration
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_username'] = $username;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = 'student';
                    $_SESSION['logged_in_at'] = time();
                    
                    $success = 'Account created successfully! Redirecting...';
                    
                    // Redirect to dashboard
                    redirect(APP_URL . '/dashboard/index.php');
                } else {
                    $error = 'An error occurred. Please try again.';
                }
            }
        }
    }
}

define('PAGE_TITLE', 'Create Account');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--gray-50);
        }
        .auth-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 480px;
            padding: 2.5rem;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }
        .auth-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            color: var(--gray-500);
        }
        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .terms-checkbox input {
            margin-top: 0.25rem;
        }
        .terms-checkbox label {
            font-size: 0.875rem;
            color: var(--gray-600);
            line-height: 1.5;
        }
        .terms-checkbox a {
            color: var(--primary);
        }
        .password-strength {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }
        .password-strength-text {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="/" class="auth-logo">
                    <div class="logo-icon" style="width: 40px; height: 40px; font-size: 1rem;">DT</div>
                    <span>Data Tutors</span>
                </a>
                <h1 class="auth-title">Create Your Account</h1>
                <p class="auth-subtitle">Start your learning journey today</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           placeholder="John Doe" required 
                           value="<?= sanitize($_POST['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input" 
                           placeholder="johndoe" required 
                           value="<?= sanitize($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="you@example.com" required 
                           value="<?= sanitize($_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="At least 6 characters" required minlength="6"
                           oninput="checkPasswordStrength(this.value)">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strength-bar"></div>
                    </div>
                    <div class="password-strength-text" id="strength-text"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="form-input" placeholder="Confirm your password" required>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        I agree to the <a href="/terms.php">Terms of Service</a> and 
                        <a href="/privacy.php">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    Create Account
                </button>
            </form>
            
            <div class="auth-footer">
                <p style="color: var(--gray-500); margin-bottom: 0;">
                    Already have an account? 
                    <a href="/auth/login.php">Sign in</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function checkPasswordStrength(password) {
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            
            let strength = 0;
            let color = '#ef4444';
            let message = '';
            
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch (strength) {
                case 0:
                case 1:
                    color = '#ef4444';
                    message = 'Weak';
                    break;
                case 2:
                    color = '#f59e0b';
                    message = 'Fair';
                    break;
                case 3:
                    color = '#10b981';
                    message = 'Good';
                    break;
                case 4:
                case 5:
                    color = '#059669';
                    message = 'Strong';
                    break;
            }
            
            bar.style.width = (strength * 20) + '%';
            bar.style.background = color;
            text.textContent = message;
            text.style.color = color;
        }
    </script>
</body>
</html>
