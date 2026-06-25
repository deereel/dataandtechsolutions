<?php
/**
 * Data Tutors - Admin Settings
 * Manage admin panel settings
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - only super admins can access settings
if (!isAdminLoggedIn() || !isSuperAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get current admin user
$currentAdmin = getCurrentAdmin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Update profile
    if ($action === 'profile') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        
        if (empty($name) || empty($email)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $updateData = [
                'name' => $name,
                'email' => $email
            ];
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } else {
                    $updateData['password'] = $_POST['password'];
                }
            }
            
            if (empty($error)) {
                User::update($currentAdmin['id'], $updateData);
                $_SESSION['admin_name'] = $name;
                $_SESSION['admin_email'] = $email;
                $success = 'Profile updated successfully.';
            }
        }
    }
    
    // Update site settings
    elseif ($action === 'settings') {
        $siteName = sanitize($_POST['site_name'] ?? '');
        $siteEmail = sanitize($_POST['site_email'] ?? '');
        
        // Update config file
        $configPath = '../config/config.php';
        $configContent = file_get_contents($configPath);
        
        $configContent = preg_replace("/define\('APP_NAME', '[^']*'\)/", "define('APP_NAME', '$siteName')", $configContent);
        $configContent = preg_replace("/define\('APP_EMAIL', '[^']*'\)/", "define('APP_EMAIL', '$siteEmail')", $configContent);
        
        file_put_contents($configPath, $configContent);
        $success = 'Settings updated successfully.';
    }
}

// Get site stats
$stats = [
    'total_users' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM users"),
    'total_courses' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM courses"),
    'total_enrollments' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM enrollments"),
    'total_forum_posts' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_questions")
];

define('PAGE_TITLE', 'Admin Settings');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .admin-page {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            background: var(--gray-900);
            color: white;
            padding: 1.5rem;
        }
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: white;
        }
        .admin-nav {
            list-style: none;
        }
        .admin-nav-item {
            margin-bottom: 0.25rem;
        }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            color: var(--gray-400);
            transition: var(--transition);
        }
        .admin-nav-link:hover,
        .admin-nav-link.active {
            background: var(--gray-800);
            color: white;
        }
        .admin-nav-link svg {
            width: 20px;
            height: 20px;
        }
        .admin-main {
            padding: 2rem;
            background: var(--gray-50);
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .stat-label {
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        .admin-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .card-title {
            font-weight: 600;
            font-size: 1.125rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
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
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .user-name {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .user-email {
            color: var(--gray-500);
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        
        @media (max-width: 1024px) {
            .admin-page {
                grid-template-columns: 1fr;
            }
            .admin-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="admin-page">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <?php include 'includes/sidebar.php'; ?>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <div class="page-header">
                <h1>Admin Settings</h1>
                <p style="color: var(--gray-500);">Manage your admin account and site settings</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_users'] ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_courses'] ?></div>
                    <div class="stat-label">Total Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_enrollments'] ?></div>
                    <div class="stat-label">Enrollments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_forum_posts'] ?></div>
                    <div class="stat-label">Forum Posts</div>
                </div>
            </div>
            
            <!-- Profile Settings -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Admin Profile</h3>
                </div>
                <div class="card-body">
                    <div class="user-info">
                        <div class="user-avatar"><?= strtoupper(substr($currentAdmin['name'], 0, 2)) ?></div>
                        <div>
                            <div class="user-name"><?= sanitize($currentAdmin['name']) ?></div>
                            <div class="user-email"><?= sanitize($currentAdmin['email']) ?></div>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="profile">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-input" value="<?= sanitize($currentAdmin['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-input" value="<?= sanitize($currentAdmin['email']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" id="password" name="password" class="form-input" placeholder="Leave blank to keep current password">
                                <small style="color: var(--gray-500); font-size: 0.75rem;">Minimum 6 characters</small>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-input" value="<?= ucfirst(str_replace('_', ' ', $currentAdmin['role'])) ?>" disabled>
                                <small style="color: var(--gray-500); font-size: 0.75rem;">Contact super admin to change role</small>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Site Settings -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Site Settings</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="settings">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" id="site_name" name="site_name" class="form-input" value="<?= APP_NAME ?>">
                            </div>
                            <div class="form-group">
                                <label for="site_email" class="form-label">Contact Email</label>
                                <input type="email" id="site_email" name="site_email" class="form-input" value="<?= APP_EMAIL ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">System Information</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 0.25rem;">PHP Version</div>
                            <div style="font-weight: 500;"><?= PHP_VERSION ?></div>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 0.25rem;">Server Software</div>
                            <div style="font-weight: 500;"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></div>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 0.25rem;">Database</div>
                            <div style="font-weight: 500;">MySQL / MariaDB</div>
                        </div>
                        <div>
                            <div style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 0.25rem;">Admin Since</div>
                            <div style="font-weight: 500;"><?= formatDate($currentAdmin['created_at'] ?? date('Y-m-d')) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
