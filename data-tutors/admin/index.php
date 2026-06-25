 <?php
/**
 * Data Tutors - Admin Dashboard
 * Admin panel for managing courses, users, and content
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - only admins can access
if (!isAdminLoggedIn() || !isAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get stats
$currentUser = getCurrentAdmin();
$stats = [
    'users' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM users"),
    'courses' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM courses"),
    'enrollments' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM enrollments"),
    'forum_posts' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_questions WHERE is_deleted = 0"),
    'admins' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM users WHERE role IN ('super_admin', 'admin', 'tutor')"),
];

// Role badge colors
$roleColors = [
    'super_admin' => 'badge-danger',
    'admin' => 'badge-warning',
    'tutor' => 'badge-info',
    'instructor' => 'badge-info',
    'student' => 'badge-success'
];

define('PAGE_TITLE', 'Admin Dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="theme-color" content="#1e3a5f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/icon-192.png">
    <link rel="apple-touch-icon" href="/assets/images/icon-192.png">
    
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    
    <script src="<?= APP_URL ?>/assets/js/app.js"></script>
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
        .admin-header {
            margin-bottom: 2rem;
        }
        .admin-header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
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
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
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
    <!-- PWA Install Banner -->
    <div id="install-banner" class="hidden" style="
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, #1e3a5f 0%, #0f1f38 100%);
        color: white;
        padding: 1rem;
        text-align: center;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    ">
        <span>Install Data Tutors Admin for a better experience!</span>
        <button onclick="App.installPWA()" style="
            background: white;
            color: #1e3a5f;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        ">Install</button>
        <button onclick="document.getElementById('install-banner').classList.add('hidden')" style="
            background: transparent;
            color: white;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            font-size: 1.25rem;
        ">×</button>
    </div>
    
    <div class="admin-page">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <?php include 'includes/sidebar.php'; ?>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <p style="color: var(--gray-500);">Welcome to the Data Tutors admin panel</p>
            </div>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['users'] ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['courses'] ?></div>
                    <div class="stat-label">Total Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['enrollments'] ?></div>
                    <div class="stat-label">Total Enrollments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['forum_posts'] ?></div>
                    <div class="stat-label">Forum Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['admins'] ?></div>
                    <div class="stat-label">Admin Users</div>
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Users</h3>
                    <a href="/admin/users.php" class="btn btn-sm btn-secondary">View All</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentUsers = DatabaseConnection::fetchAll("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
                            foreach ($recentUsers as $user):
                            ?>
                            <tr>
                                <td><?= sanitize($user['name']) ?></td>
                                <td><?= sanitize($user['email']) ?></td>
                                <td><span class="badge <?= $roleColors[$user['role']] ?? 'badge-success' ?>"><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></span></td>
                                <td><?= formatDate($user['created_at']) ?></td>
                                <td><span class="badge <?= $user['status'] == 'active' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($user['status']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Recent Courses -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Courses</h3>
                    <a href="/admin/courses.php" class="btn btn-sm btn-secondary">Manage Courses</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentCourses = DatabaseConnection::fetchAll("SELECT * FROM courses ORDER BY created_at DESC LIMIT 5");
                            foreach ($recentCourses as $course):
                            ?>
                            <tr>
                                <td><?= sanitize($course['title']) ?></td>
                                <td><?= ucfirst(str_replace('-', ' ', $course['category'])) ?></td>
                                <td><?= ucfirst($course['level']) ?></td>
                                <td>$<?= number_format($course['price'], 2) ?></td>
                                <td><span class="badge <?= $course['published'] ? 'badge-success' : 'badge-warning' ?>"><?= $course['published'] ? 'Published' : 'Draft' ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
