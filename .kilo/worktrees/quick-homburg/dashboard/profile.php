<?php
/**
 * Data Tutors - User Profile Page
 * View and edit user profile
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check
if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php');
}

$currentUser = getCurrentUser();
$userId = $currentUser['id'];

// Get full user details
$user = DatabaseConnection::query("SELECT * FROM users WHERE id = ?", [$userId])->fetch();

// Handle form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $bio = sanitize($_POST['bio'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate required fields
    if (empty($name)) {
        $error = 'Name is required';
    } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        // Check if password change is requested
        if (!empty($currentPassword)) {
            if (!password_verify($currentPassword, $user['password'])) {
                $error = 'Current password is incorrect';
            } elseif (empty($newPassword)) {
                $error = 'New password is required';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters';
            } else {
                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                DatabaseConnection::query("UPDATE users SET name = ?, phone = ?, bio = ?, password = ? WHERE id = ?", [$name, $phone, $bio, $hashedPassword, $userId]);
                $success = 'Profile and password updated successfully!';
            }
        } else {
            // Update profile without password change
            DatabaseConnection::query("UPDATE users SET name = ?, phone = ?, bio = ? WHERE id = ?", [$name, $phone, $bio, $userId]);
            $success = 'Profile updated successfully!';
        }
        
        // Log activity
        logActivity($userId, 'profile_update', 'users', $userId);
    }
}

define('PAGE_TITLE', 'My Profile');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#2563eb">
    <meta name="description" content="Data Tutors - View and edit your profile">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Data Tutors">
    
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    
    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/icon-192.png">
    <link rel="apple-touch-icon" href="/assets/images/icon-192.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
    
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 auto 1rem;
            overflow: hidden;
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            text-align: center;
        }
        .profile-role {
            text-align: center;
            opacity: 0.9;
            margin-top: 0.25rem;
        }
        .profile-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .profile-form-group {
            margin-bottom: 1.5rem;
        }
        .profile-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-700);
        }
        .profile-form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        .profile-form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .profile-form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        .profile-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--gray-200);
        }
        .password-section {
            background: var(--gray-50);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-top: 2rem;
        }
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .profile-stat {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: var(--radius);
            text-align: center;
        }
        .profile-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        .profile-stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="profile-avatar">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= sanitize($user['avatar']) ?>" alt="<?= sanitize($user['name']) ?>">
                <?php else: ?>
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                <?php endif; ?>
            </div>
            <h1 class="profile-name"><?= sanitize($user['name']) ?></h1>
            <p class="profile-role"><?= ucfirst($user['role']) ?> • <?= sanitize($user['email']) ?></p>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="container" style="padding-bottom: 4rem;">
        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-bottom: 1.5rem;"><?= sanitize($success) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-bottom: 1.5rem;"><?= sanitize($error) ?></div>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem;">
            <!-- Profile Form -->
            <div class="profile-card">
                <form method="POST" data-ajax>
                    <h2 class="profile-section-title">Profile Information</h2>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="profile-form-input" 
                               value="<?= sanitize($user['name']) ?>" required>
                    </div>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="email">Email Address</label>
                        <input type="email" id="email" class="profile-form-input" 
                               value="<?= sanitize($user['email']) ?>" disabled>
                        <small style="color: var(--gray-500);">Email cannot be changed</small>
                    </div>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="profile-form-input" 
                               value="<?= sanitize($user['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
                    </div>
                    
                    <div class="profile-form-group">
                        <label class="profile-form-label" for="bio">Bio</label>
                        <textarea id="bio" name="bio" class="profile-form-input profile-form-textarea" 
                                  placeholder="Tell us about yourself..."><?= sanitize($user['bio'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        Save Changes
                    </button>
                    
                    <!-- Password Change Section -->
                    <div class="password-section">
                        <h3 class="profile-section-title">Change Password</h3>
                        <p style="color: var(--gray-600); margin-bottom: 1rem;">
                            Leave password fields empty if you don't want to change your password.
                        </p>
                        
                        <div class="profile-form-group">
                            <label class="profile-form-label" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" 
                                   class="profile-form-input" placeholder="Enter current password">
                        </div>
                        
                        <div class="profile-form-group">
                            <label class="profile-form-label" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" 
                                   class="profile-form-input" placeholder="Enter new password (min 6 characters)">
                        </div>
                        
                        <div class="profile-form-group">
                            <label class="profile-form-label" for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="profile-form-input" placeholder="Confirm new password">
                        </div>
                        
                        <button type="submit" class="btn btn-secondary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Sidebar -->
            <div>
                <!-- Account Info -->
                <div class="profile-card">
                    <h3 class="profile-section-title">Account Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem;">Member Since</div>
                            <div style="font-weight: 500;"><?= formatDate($user['created_at'] ?? null) ?></div>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem;">Last Login</div>
                            <div style="font-weight: 500;">
                                <?php if (!empty($user['last_login'])): ?>
                                    <?= timeAgo($user['last_login']) ?>
                                <?php else: ?>
                                    Never
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem;">Account Status</div>
                            <span class="badge <?= ($user['status'] ?? 'active') === 'active' ? 'badge-success' : 'badge-warning' ?>">
                                <?= ucfirst($user['status'] ?? 'active') ?>
                            </span>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem;">Email Verified</div>
                            <span class="badge <?= !empty($user['email_verified']) ? 'badge-success' : 'badge-warning' ?>">
                                <?= !empty($user['email_verified']) ? 'Yes' : 'No' ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="profile-card">
                    <h3 class="profile-section-title">Your Activity</h3>
                    <div class="profile-stats">
                        <?php
                        $enrollments = DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM enrollments WHERE user_id = ?", [$userId]);
                        $certificates = DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM certificates WHERE user_id = ?", [$userId]);
                        $forumPosts = DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_questions WHERE user_id = ?", [$userId]);
                        ?>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?= $enrollments ?></div>
                            <div class="profile-stat-label">Courses</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?= $certificates ?></div>
                            <div class="profile-stat-label">Certificates</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?= $forumPosts ?></div>
                            <div class="profile-stat-label">Posts</div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="profile-card">
                    <h3 class="profile-section-title">Quick Links</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="/dashboard/index.php" class="btn btn-secondary" style="text-align: left;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            My Dashboard
                        </a>
                        <a href="/course/index.php" class="btn btn-secondary" style="text-align: left;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Browse Courses
                        </a>
                        <a href="/forum/index.php" class="btn btn-secondary" style="text-align: left;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            Community Forum
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
