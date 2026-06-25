<?php
/**
 * Data Tutors - Admin Login Page
 * Dedicated login page for admin panel access
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Redirect if already logged in as admin
if (isAdminLoggedIn()) {
    redirect(APP_URL . '/admin/index.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = sanitize($_POST['email'] ?? ''); // Can be username or email
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validate input
    if (empty($login) || empty($password)) {
        $error = 'Please enter both username/email and password.';
    } else {
        // Check if login is email or username
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
        
        if ($isEmail) {
            // Verify credentials by email
            $user = User::verifyPassword($login, $password);
        } else {
            // Verify credentials by username
            $user = User::verifyPasswordByUsername($login, $password);
        }
        
        if ($user) {
            if ($user['status'] === 'blocked') {
                $error = 'Your account has been blocked. Please contact support.';
            } elseif ($user['status'] === 'inactive') {
                $error = 'Your account is inactive. Please contact support.';
            } elseif (!in_array($user['role'], ['super_admin', 'admin', 'tutor'])) {
                $error = 'You do not have admin privileges to access this area.';
            } else {
                // Set admin session (separate from user session)
                loginAdmin($user);
                
                // Update last login
                Database::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
                
                // Handle remember me
                if ($remember) {
                    $token = generateToken(64);
                    $tokenHash = hash('sha256', $token);
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    // Store remember token
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
                }
                
                // Log activity
                logActivity($user['id'], 'admin_login', 'admin', $user['id']);
                
                // Redirect to admin dashboard or intended page
                $redirect = $_SESSION['redirect_url'] ?? APP_URL . '/admin/index.php';
                unset($_SESSION['redirect_url']);
                
                redirect($redirect);
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

define('PAGE_TITLE', 'Admin Login');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f1f38 100%);
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
            text-decoration: none;
        }
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-100);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-600);
            margin-bottom: 1rem;
        }
        .admin-badge svg {
            width: 16px;
            height: 16px;
        }
        .auth-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
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
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.875rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        .btn-lg {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: var(--gray-500);
            text-decoration: none;
            font-size: 0.875rem;
        }
        .back-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="/" class="auth-logo">
                    <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Data Tutors" style="height: 50px; margin-bottom: 1rem;">
                </a>
                <div class="admin-badge">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Admin Panel
                </div>
                <h1 class="auth-title">Admin Login</h1>
<p class="auth-subtitle">Enter your credentials to access the admin panel</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email" class="form-label">Username or Email</label>
                    <input type="text" id="email" name="email" class="form-input" 
                           placeholder="admin@example.com or admin" required autofocus>
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
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">
                    Sign In to Admin Panel
                </button>
            </form>
            
            <div class="auth-footer">
                <p style="color: var(--gray-500); margin-bottom: 0;">
                    <a href="/auth/login.php">User Login</a> | 
                    <a href="/auth/forgot-password.php">Forgot password?</a>
                </p>
            </div>
            
            <a href="/" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Back to Website
            </a>
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
