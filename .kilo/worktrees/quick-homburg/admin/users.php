<?php
/**
 * Data Tutors - Users Management
 * Consolidated user management page with Students and Admin tabs
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - only admins can access
if (!isAdminLoggedIn() || !isAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get current admin user
$currentAdmin = getCurrentAdmin();

$error = '';
$success = '';

// Get tab from URL parameter (default to 'students')
$activeTab = $_GET['tab'] ?? 'students';

// Handle form submission - Only super admin can perform CRUD actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isSuperAdmin()) {
    $action = $_POST['action'] ?? '';
    $userType = $_POST['user_type'] ?? 'student'; // 'student' or 'admin'
    
    // Add new user (student)
    if ($action === 'add' && $userType === 'student') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = sanitize($_POST['phone'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($name) || empty($email) || empty($password)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            // Check if email already exists
            $existing = DatabaseConnection::fetch("SELECT id FROM users WHERE email = ?", [$email]);
            if ($existing) {
                $error = 'An account with this email already exists.';
            } else {
                $userId = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'role' => 'student',
                    'status' => $status
                ]);
                
                if ($userId) {
                    logActivity($_SESSION['admin_id'], 'create_user', 'users', $userId, null, [
                        'name' => $name,
                        'email' => $email,
                        'role' => 'student'
                    ]);
                    $success = 'Student user created successfully.';
                } else {
                    $error = 'Failed to create student user.';
                }
            }
        }
    }
    
    // Add new admin user
    elseif ($action === 'add' && $userType === 'admin') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = sanitize($_POST['role'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (!in_array($role, ['super_admin', 'admin', 'tutor'])) {
            $error = 'Invalid role selected.';
        } else {
            $existing = DatabaseConnection::fetch("SELECT id FROM users WHERE email = ?", [$email]);
            if ($existing) {
                $error = 'An account with this email already exists.';
            } else {
                $userId = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role,
                    'status' => $status
                ]);
                
                if ($userId) {
                    logActivity($_SESSION['admin_id'], 'create_admin', 'users', $userId, null, [
                        'name' => $name,
                        'email' => $email,
                        'role' => $role
                    ]);
                    $success = 'Admin user created successfully.';
                } else {
                    $error = 'Failed to create admin user.';
                }
            }
        }
    }
    
    // Update student user
    elseif ($action === 'update' && $userType === 'student') {
        $userId = intval($_POST['user_id'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if ($userId <= 0) {
            $error = 'Invalid user ID.';
        } elseif (empty($name) || empty($email)) {
            $error = 'Please fill in all required fields.';
        } else {
            $oldUser = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            
            $updateData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'status' => $status
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
                User::update($userId, $updateData);
                logActivity($_SESSION['admin_id'], 'update_user', 'users', $userId, $oldUser, $updateData);
                $success = 'Student user updated successfully.';
            }
        }
    }
    
    // Update admin user
    elseif ($action === 'update' && $userType === 'admin') {
        $userId = intval($_POST['user_id'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $role = sanitize($_POST['role'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if ($userId <= 0) {
            $error = 'Invalid user ID.';
        } elseif (empty($name) || empty($email) || empty($role)) {
            $error = 'Please fill in all required fields.';
        } elseif (!in_array($role, ['super_admin', 'admin', 'tutor'])) {
            $error = 'Invalid role selected.';
        } else {
            $oldUser = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            
            $updateData = [
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'status' => $status
            ];
            
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } else {
                    $updateData['password'] = $_POST['password'];
                }
            }
            
            if (empty($error)) {
                User::update($userId, $updateData);
                logActivity($_SESSION['admin_id'], 'update_admin', 'users', $userId, $oldUser, $updateData);
                $success = 'Admin user updated successfully.';
            }
        }
    }
    
    // Delete student user
    elseif ($action === 'delete' && $userType === 'student') {
        $userId = intval($_POST['user_id'] ?? 0);
        
        if ($userId <= 0) {
            $error = 'Invalid user ID.';
        } else {
            $user = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            if ($user) {
                DatabaseConnection::delete('users', 'id = ?', [$userId]);
                logActivity($_SESSION['admin_id'], 'delete_user', 'users', $userId, $user, null);
                $success = 'Student user deleted successfully.';
            } else {
                $error = 'User not found.';
            }
        }
    }
    
    // Delete admin user
    elseif ($action === 'delete' && $userType === 'admin') {
        $userId = intval($_POST['user_id'] ?? 0);
        
        if ($userId <= 0) {
            $error = 'Invalid user ID.';
        } elseif ($userId == $_SESSION['admin_id']) {
            $error = 'You cannot delete your own account.';
        } else {
            $user = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            if ($user) {
                DatabaseConnection::delete('users', 'id = ?', [$userId]);
                logActivity($_SESSION['admin_id'], 'delete_admin', 'users', $userId, $user, null);
                $success = 'Admin user deleted successfully.';
            } else {
                $error = 'User not found.';
            }
        }
    }
}

// Get all students (non-admin users)
$students = DatabaseConnection::fetchAll(
    "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC"
);

// Get all admin users
$adminUsers = DatabaseConnection::fetchAll(
    "SELECT * FROM users WHERE role IN ('super_admin', 'admin', 'tutor') ORDER BY role, name"
);

define('PAGE_TITLE', 'User Management');
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        .btn-secondary:hover {
            background: var(--gray-300);
        }
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-sm {
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-weight: 600;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-100);
        }
        th {
            background: var(--gray-50);
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        tr:hover {
            background: var(--gray-50);
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        .badge-secondary { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
        
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
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h2 {
            font-size: 1.25rem;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
        }
        .modal-body {
            padding: 1.5rem;
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
        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: 500;
            color: var(--gray-900);
        }
        .user-email {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Tabs */
        .tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--gray-200);
        }
        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--gray-500);
            cursor: pointer;
            position: relative;
            transition: var(--transition);
        }
        .tab:hover {
            color: var(--primary);
        }
        .tab.active {
            color: var(--primary);
        }
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        
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
                <div>
                    <h1>User Management</h1>
                    <p style="color: var(--gray-500);">Manage all users and admin accounts</p>
                </div>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab <?= $activeTab === 'students' ? 'active' : '' ?>" onclick="switchTab('students')">
                    Students (<?= count($students) ?>)
                </button>
                <button class="tab <?= $activeTab === 'admins' ? 'active' : '' ?>" onclick="switchTab('admins')">
                    Admin Users (<?= count($adminUsers) ?>)
                </button>
            </div>
            
            <!-- Students Tab -->
            <div id="students-tab" class="tab-content <?= $activeTab === 'students' ? 'active' : '' ?>">
                <?php if (!isSuperAdmin()): ?>
                    <div class="alert alert-danger">
                        You don't have permission to manage students. Only super admins can perform CRUD actions on users.
                    </div>
                <?php else: ?>
                    <div class="page-header" style="margin-bottom: 1rem;">
                        <div>
                            <h2 style="font-size: 1.25rem;">All Students</h2>
                            <p style="color: var(--gray-500); font-size: 0.875rem;">Registered student users on the platform</p>
                        </div>
                        <button class="btn btn-primary" onclick="openStudentModal('add')">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Student
                        </button>
                    </div>
                    
                    <div class="admin-card">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                     <tr style="cursor: pointer;" onclick="window.location.href='<?= APP_URL ?>/admin/student-detail.php?id=<?= $student['id'] ?>'">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar"><?= strtoupper(substr($student['name'], 0, 2)) ?></div>
                                                <div class="user-details">
                                                    <span class="user-name"><?= sanitize($student['name']) ?></span>
                                                    <span class="user-email"><?= sanitize($student['email']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= sanitize($student['phone'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge <?= $student['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($student['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $student['last_login'] ? formatDate($student['last_login']) : 'Never' ?></td>
                                        <td><?= formatDate($student['created_at']) ?></td>
                                        <td>
                                            <div class="actions">
                                                <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); openStudentModal('edit', <?= $student['id'] ?>)">
                                                    Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteStudent(<?= $student['id'] ?>)">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Admins Tab -->
            <div id="admins-tab" class="tab-content <?= $activeTab === 'admins' ? 'active' : '' ?>">
                <?php if (!isSuperAdmin()): ?>
                    <div class="alert alert-danger">
                        You don't have permission to manage admin users. Only super admins can perform CRUD actions.
                    </div>
                <?php else: ?>
                    <div class="page-header" style="margin-bottom: 1rem;">
                        <div>
                            <h2 style="font-size: 1.25rem;">All Admin Users</h2>
                            <p style="color: var(--gray-500); font-size: 0.875rem;">Manage admin accounts with different permission levels</p>
                        </div>
                        <button class="btn btn-primary" onclick="openAdminModal('add')">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Admin User
                        </button>
                    </div>
                    
                    <div class="admin-card">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($adminUsers as $admin): ?>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar"><?= strtoupper(substr($admin['name'], 0, 2)) ?></div>
                                                <div class="user-details">
                                                    <span class="user-name"><?= sanitize($admin['name']) ?></span>
                                                    <span class="user-email"><?= sanitize($admin['email']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $roleClass = [
                                                'super_admin' => 'badge-danger',
                                                'admin' => 'badge-warning',
                                                'tutor' => 'badge-info'
                                            ];
                                            $roleLabel = [
                                                'super_admin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'tutor' => 'Tutor'
                                            ];
                                            ?>
                                            <span class="badge <?= $roleClass[$admin['role']] ?? 'badge-success' ?>">
                                                <?= $roleLabel[$admin['role']] ?? ucfirst($admin['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $admin['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($admin['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= $admin['last_login'] ? formatDate($admin['last_login']) : 'Never' ?></td>
                                        <td><?= formatDate($admin['created_at']) ?></td>
                                        <td>
                                            <div class="actions">
                                                <button class="btn btn-secondary btn-sm" onclick="openAdminModal('edit', <?= $admin['id'] ?>)">
                                                    Edit
                                                </button>
                                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                                <button class="btn btn-danger btn-sm" onclick="deleteAdmin(<?= $admin['id'] ?>)">
                                                    Delete
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Student Add/Edit Modal -->
    <div class="modal" id="studentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="studentModalTitle">Add Student</h2>
                <button class="modal-close" onclick="closeStudentModal()">&times;</button>
            </div>
            <form method="POST" id="studentForm">
                <div class="modal-body">
                    <input type="hidden" name="action" id="studentFormAction" value="add">
                    <input type="hidden" name="user_type" value="student">
                    <input type="hidden" name="user_id" id="studentUserId" value="">
                    
                    <div class="form-group">
                        <label for="studentName" class="form-label">Full Name *</label>
                        <input type="text" id="studentName" name="name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="studentEmail" class="form-label">Email Address *</label>
                        <input type="email" id="studentEmail" name="email" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="studentPassword" class="form-label" id="studentPasswordLabel">Password *</label>
                        <input type="password" id="studentPassword" name="password" class="form-input">
                        <small style="color: var(--gray-500); font-size: 0.75rem;" id="studentPasswordHelp">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="studentPhone" class="form-label">Phone Number</label>
                        <input type="tel" id="studentPhone" name="phone" class="form-input" placeholder="+1 (555) 000-0000">
                    </div>
                    
                    <div class="form-group">
                        <label for="studentStatus" class="form-label">Status</label>
                        <select id="studentStatus" name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeStudentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Student Delete Modal -->
    <div class="modal" id="studentDeleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="modal-close" onclick="closeStudentDeleteModal()">&times;</button>
            </div>
            <form method="POST" id="studentDeleteForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_type" value="student">
                    <input type="hidden" name="user_id" id="studentDeleteUserId" value="">
                    <p>Are you sure you want to delete this student? This action cannot be undone.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeStudentDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Admin Add/Edit Modal -->
    <div class="modal" id="adminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="adminModalTitle">Add Admin User</h2>
                <button class="modal-close" onclick="closeAdminModal()">&times;</button>
            </div>
            <form method="POST" id="adminForm">
                <div class="modal-body">
                    <input type="hidden" name="action" id="adminFormAction" value="add">
                    <input type="hidden" name="user_type" value="admin">
                    <input type="hidden" name="user_id" id="adminUserId" value="">
                    
                    <div class="form-group">
                        <label for="adminName" class="form-label">Full Name *</label>
                        <input type="text" id="adminName" name="name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="adminEmail" class="form-label">Email Address *</label>
                        <input type="email" id="adminEmail" name="email" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="adminPassword" class="form-label" id="adminPasswordLabel">Password *</label>
                        <input type="password" id="adminPassword" name="password" class="form-input">
                        <small style="color: var(--gray-500); font-size: 0.75rem;" id="adminPasswordHelp">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="adminRole" class="form-label">Role *</label>
                        <select id="adminRole" name="role" class="form-select" required>
                            <option value="tutor">Tutor</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                        <small style="color: var(--gray-500); font-size: 0.75rem;">
                            <strong>Super Admin:</strong> Full access to all features<br>
                            <strong>Admin:</strong> Management access<br>
                            <strong>Tutor:</strong> Course and content management
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="adminStatus" class="form-label">Status</label>
                        <select id="adminStatus" name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Admin User</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Admin Delete Modal -->
    <div class="modal" id="adminDeleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="modal-close" onclick="closeAdminDeleteModal()">&times;</button>
            </div>
            <form method="POST" id="adminDeleteForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_type" value="admin">
                    <input type="hidden" name="user_id" id="adminDeleteUserId" value="">
                    <p>Are you sure you want to delete this admin user? This action cannot be undone.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeAdminDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Admin User</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const studentsData = <?= json_encode($students) ?>;
        const adminsData = <?= json_encode($adminUsers) ?>;
        
        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            document.querySelector(`.tab[onclick="switchTab('${tab}')"]`).classList.add('active');
            document.getElementById(`${tab}-tab`).classList.add('active');
            
            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }
        
        // Student Modal Functions
        function openStudentModal(action, userId = null) {
            const modal = document.getElementById('studentModal');
            const form = document.getElementById('studentForm');
            const title = document.getElementById('studentModalTitle');
            const passwordLabel = document.getElementById('studentPasswordLabel');
            const passwordHelp = document.getElementById('studentPasswordHelp');
            const passwordInput = document.getElementById('studentPassword');
            
            form.reset();
            document.getElementById('studentFormAction').value = action;
            document.getElementById('studentUserId').value = '';
            
            if (action === 'add') {
                title.textContent = 'Add Student';
                passwordLabel.textContent = 'Password *';
                passwordHelp.textContent = 'Minimum 6 characters';
                passwordInput.required = true;
            } else {
                title.textContent = 'Edit Student';
                passwordLabel.textContent = 'Password';
                passwordHelp.textContent = 'Leave blank to keep current password';
                passwordInput.required = false;
                
                const student = studentsData.find(s => s.id === userId);
                if (student) {
                    document.getElementById('studentUserId').value = student.id;
                    document.getElementById('studentName').value = student.name;
                    document.getElementById('studentEmail').value = student.email;
                    document.getElementById('studentPhone').value = student.phone || '';
                    document.getElementById('studentStatus').value = student.status;
                }
            }
            
            modal.classList.add('active');
        }
        
        function closeStudentModal() {
            document.getElementById('studentModal').classList.remove('active');
        }
        
        function deleteStudent(userId) {
            document.getElementById('studentDeleteUserId').value = userId;
            document.getElementById('studentDeleteModal').classList.add('active');
        }
        
        function closeStudentDeleteModal() {
            document.getElementById('studentDeleteModal').classList.remove('active');
        }
        
        // Admin Modal Functions
        function openAdminModal(action, userId = null) {
            const modal = document.getElementById('adminModal');
            const form = document.getElementById('adminForm');
            const title = document.getElementById('adminModalTitle');
            const passwordLabel = document.getElementById('adminPasswordLabel');
            const passwordHelp = document.getElementById('adminPasswordHelp');
            const passwordInput = document.getElementById('adminPassword');
            
            form.reset();
            document.getElementById('adminFormAction').value = action;
            document.getElementById('adminUserId').value = '';
            
            if (action === 'add') {
                title.textContent = 'Add Admin User';
                passwordLabel.textContent = 'Password *';
                passwordHelp.textContent = 'Minimum 6 characters';
                passwordInput.required = true;
            } else {
                title.textContent = 'Edit Admin User';
                passwordLabel.textContent = 'Password';
                passwordHelp.textContent = 'Leave blank to keep current password';
                passwordInput.required = false;
                
                const admin = adminsData.find(a => a.id === userId);
                if (admin) {
                    document.getElementById('adminUserId').value = admin.id;
                    document.getElementById('adminName').value = admin.name;
                    document.getElementById('adminEmail').value = admin.email;
                    document.getElementById('adminRole').value = admin.role;
                    document.getElementById('adminStatus').value = admin.status;
                }
            }
            
            modal.classList.add('active');
        }
        
        function closeAdminModal() {
            document.getElementById('adminModal').classList.remove('active');
        }
        
        function deleteAdmin(userId) {
            document.getElementById('adminDeleteUserId').value = userId;
            document.getElementById('adminDeleteModal').classList.add('active');
        }
        
        function closeAdminDeleteModal() {
            document.getElementById('adminDeleteModal').classList.remove('active');
        }
        
        // Close modals on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
