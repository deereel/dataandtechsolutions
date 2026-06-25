<?php
/**
 * Data Tutors - Login Page
 */

// Start session and include configuration
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
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Verify credentials
        $user = User::verifyPassword($email, $password);
        
        if ($user) {
            if ($user['status'] === 'blocked') {
                $error = 'Your account has been blocked. Please contact support.';
            } elseif ($user['status'] === 'inactive') {
                $error = 'Your account is inactive. Please contact support.';
            } else {
                // Set user session
                loginUser($user);
                
                // Update last login
                DatabaseConnection::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
                
                // Handle remember me
                if ($remember) {
                    $token = generateToken(64);
                    $tokenHash = hash('sha256', $token);
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    // Store remember token
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
                    
                    // Save token hash to database (you might want to add a remember_tokens table)
                }
                
                // Log activity
                logActivity($user['id'], 'login', 'users', $user['id']);
                
                // Redirect to dashboard or intended page
                $redirect = $_SESSION['redirect_url'] ?? APP_URL . '/dashboard/index.php';
                unset($_SESSION['redirect_url']);
                
                redirect($redirect);
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

define('PAGE_TITLE', 'Login');
$page_scripts = ['/assets/js/auth.js'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= APP_NAME ?></title>
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
            max-width: 420px;
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
        .auth-form {
            margin-bottom: 1.5rem;
        }
        .auth-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }
        .auth-footer a {
            color: var(--primary);
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle input {
            padding-right: 3rem;
        }
        .password-toggle button {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
        }
        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            background: var(--white);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }
        .social-btn:hover {
            background: var(--gray-50);
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
                <h1 class="auth-title">Welcome Back!</h1>
                <p class="auth-subtitle">Sign in to continue your learning journey</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form" data-ajax>
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           placeholder="you@example.com" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-toggle">
                        <input type="password" id="password" name="password" 
                               class="form-input" placeholder="Enter your password" required>
                        <button type="button" onclick="togglePassword('password', this)">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="remember" id="remember">
                        <span style="font-size: 0.875rem; color: var(--gray-600);">Remember me</span>
                    </label>
                    <a href="/auth/forgot-password.php" style="font-size: 0.875rem; color: var(--primary);">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                    Sign In
                </button>
            </form>
            
            <div class="auth-footer">
                <p style="color: var(--gray-500); margin-bottom: 0;">
                    Don't have an account? 
                    <a href="/auth/register.php">Create one free</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
            }
        }
    </script>
</body>
</html>
