<?php
/**
 * Data Tutors - Student Dashboard
 * User dashboard showing enrolled courses, progress, and certificates
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check
if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php');
}

$userId = $_SESSION['user_id'];

// Get enrolled courses with progress
$enrolledCourses = Enrollment::getEnrolledCourses($userId);

// Get recent quiz results
$recentQuizzes = DatabaseConnection::fetchAll("
    SELECT qr.*, q.title as quiz_title, c.title as course_title
    FROM quiz_results qr
    JOIN quizzes q ON qr.quiz_id = q.id
    LEFT JOIN courses c ON q.course_id = c.id
    WHERE qr.user_id = ?
    ORDER BY qr.completed_at DESC
    LIMIT 5
", [$userId]);

// Get certificates
$certificates = Certificate::getByUser($userId);

// Get recent forum activity
$recentForumPosts = DatabaseConnection::fetchAll("
    SELECT fq.*, c.title as course_title
    FROM forum_questions fq
    LEFT JOIN courses c ON fq.course_id = c.id
    WHERE fq.user_id = ?
    ORDER BY fq.created_at DESC
    LIMIT 3
", [$userId]);

// Get notifications
$notifications = DatabaseConnection::fetchAll("
    SELECT * FROM notifications 
    WHERE user_id = ? AND is_read = 0 
    ORDER BY created_at DESC 
    LIMIT 5
", [$userId]);

// Calculate stats
$totalCourses = count($enrolledCourses);
$completedCourses = 0;
$totalProgress = 0;
foreach ($enrolledCourses as $course) {
    if ($course['progress_percentage'] == 100) {
        $completedCourses++;
    }
    $totalProgress += floatval($course['progress_percentage'] ?? 0);
}
$avgProgress = $totalCourses > 0 ? $totalProgress / $totalCourses : 0;

define('PAGE_TITLE', 'Dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .dashboard-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--gray-50);
        }
        .dashboard-header {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 2rem;
        }
        .dashboard-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .welcome-section h1 {
            font-size: 1.75rem;
            margin-bottom: 0.25rem;
        }
        .welcome-section p {
            color: var(--gray-500);
        }
        .header-actions {
            display: flex;
            gap: 0.75rem;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
            padding-bottom: 4rem;
        }
        .dashboard-main {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        .dashboard-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .stat-icon.courses { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
        .stat-icon.progress { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-icon.certificates { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .stat-icon.quizzes { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
        }
        .section-link {
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Course Cards */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            padding: 1.5rem;
        }
        .course-card-item {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
            transition: var(--transition);
        }
        .course-card-item:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        .course-card-image {
            height: 120px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .course-card-image.excel { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .course-card-image.data-analysis { background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%); }
        .course-card-image.automation { background: linear-gradient(135deg, #ea580c 0%, #f59e0b 100%); }
        .course-card-content {
            padding: 1rem;
        }
        .course-card-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }
        .course-progress {
            margin-top: 0.75rem;
        }
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }
        
        /* List Items */
        .list-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .list-item:last-child {
            border-bottom: none;
        }
        .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .list-icon.quiz { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .list-icon.cert { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .list-icon.forum { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
        .list-info {
            flex: 1;
        }
        .list-title {
            font-weight: 500;
            color: var(--gray-800);
            font-size: 0.9rem;
        }
        .list-meta {
            font-size: 0.8rem;
            color: var(--gray-500);
        }
        .list-score {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .list-score.pass { color: var(--success); }
        .list-score.fail { color: var(--danger); }
        
        /* Certificates */
        .cert-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .cert-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .cert-info {
            flex: 1;
        }
        .cert-title {
            font-weight: 500;
            color: var(--gray-800);
        }
        .cert-date {
            font-size: 0.8rem;
            color: var(--gray-500);
        }
        .btn-view {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        /* Notifications */
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            margin-top: 0.4rem;
            flex-shrink: 0;
        }
        .notification-content {
            flex: 1;
        }
        .notification-title {
            font-weight: 500;
            color: var(--gray-800);
            font-size: 0.9rem;
        }
        .notification-time {
            font-size: 0.75rem;
            color: var(--gray-400);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .header-actions {
                width: 100%;
            }
            .header-actions .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="dashboard-page">
        <div class="dashboard-header">
            <div class="container">
                <div class="dashboard-header-content">
                    <div class="welcome-section">
                        <h1>Welcome back, <?= sanitize($_SESSION['user_name']) ?>!</h1>
                        <p>Continue your learning journey</p>
                    </div>
                    <div class="header-actions">
                        <a href="/course/index.php" class="btn btn-primary">
                            Browse Courses
                        </a>
                        <a href="/dashboard/profile.php" class="btn btn-secondary">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="dashboard-grid">
                <div class="dashboard-main">
                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon courses">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="stat-value"><?= $totalCourses ?></div>
                            <div class="stat-label">Enrolled Courses</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon progress">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="stat-value"><?= number_format($avgProgress, 0) ?>%</div>
                            <div class="stat-label">Average Progress</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon certificates">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div class="stat-value"><?= count($certificates) ?></div>
                            <div class="stat-label">Certificates Earned</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon quizzes">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="stat-value"><?= count($recentQuizzes) ?></div>
                            <div class="stat-label">Quizzes Taken</div>
                        </div>
                    </div>
                    
                    <!-- My Courses -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">My Courses</h2>
                            <a href="/course/index.php" class="section-link">Browse All →</a>
                        </div>
                        <?php if (empty($enrolledCourses)): ?>
                            <div class="empty-state">
                                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p>You haven't enrolled in any courses yet.</p>
                                <a href="/course/index.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Courses</a>
                            </div>
                        <?php else: ?>
                            <div class="course-grid">
                                <?php foreach ($enrolledCourses as $course): ?>
                                    <a href="/course/details.php?id=<?= $course['id'] ?>" class="course-card-item">
                                        <div class="course-card-image <?= $course['category'] ?>">
                                            <?php if ($course['category'] === 'excel'): ?>
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="white"><path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h4v2H7V7zm0 4h4v2H7v-2zm0 4h2v2H7v-2zm6-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2zm-4 4h4v2H9v-2zm0-8h4v2H9V7z"/></svg>
                                            <?php elseif ($course['category'] === 'data-analysis'): ?>
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="white"><path d="M3 3h18v18H3V3zm4 14h2V7H7v10zm4-4h2V7h-2v6z"/></svg>
                                            <?php else: ?>
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="white"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="course-card-content">
                                            <div class="course-card-title"><?= sanitize($course['title']) ?></div>
                                            <div class="course-progress">
                                                <div class="progress-label">
                                                    <span>Progress</span>
                                                    <span><?= number_format($course['progress_percentage'] ?? 0, 0) ?>%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: <?= $course['progress_percentage'] ?? 0 ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Recent Quiz Results -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Recent Quiz Results</h2>
                            <a href="/dashboard/quizzes.php" class="section-link">View All →</a>
                        </div>
                        <?php if (empty($recentQuizzes)): ?>
                            <div class="empty-state" style="padding: 2rem;">
                                <p>No quiz results yet.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentQuizzes as $quiz): ?>
                                <div class="list-item">
                                    <div class="list-icon quiz">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                                    </div>
                                    <div class="list-info">
                                        <div class="list-title"><?= sanitize($quiz['quiz_title']) ?></div>
                                        <div class="list-meta"><?= sanitize($quiz['course_title'] ?? 'General') ?> • <?= timeAgo($quiz['completed_at']) ?></div>
                                    </div>
                                    <div class="list-score <?= $quiz['passed'] ? 'pass' : 'fail' ?>">
                                        <?= number_format($quiz['percentage'], 0) ?>%
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-sidebar">
                    <!-- Certificates -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Certificates</h2>
                        </div>
                        <?php if (empty($certificates)): ?>
                            <div class="empty-state" style="padding: 2rem;">
                                <p style="font-size: 0.875rem;">Complete courses to earn certificates!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($certificates as $cert): ?>
                                <div class="cert-item">
                                    <div class="cert-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                    </div>
                                    <div class="cert-info">
                                        <div class="cert-title"><?= sanitize($cert['course_name']) ?></div>
                                        <div class="cert-date"><?= formatDate($cert['completion_date']) ?></div>
                                    </div>
                                    <a href="/certs/view.php?id=<?= $cert['certificate_id'] ?>" class="btn btn-outline btn-sm btn-view">View</a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Notifications</h2>
                            <a href="/dashboard/notifications.php" class="section-link">View All →</a>
                        </div>
                        <?php if (empty($notifications)): ?>
                            <div class="empty-state" style="padding: 2rem;">
                                <p style="font-size: 0.875rem;">No new notifications</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="notification-item">
                                    <div class="notification-dot"></div>
                                    <div class="notification-content">
                                        <div class="notification-title"><?= sanitize($notif['title']) ?></div>
                                        <div class="notification-time"><?= timeAgo($notif['created_at']) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="section-card">
                        <div class="section-header">
                            <h2 class="section-title">Quick Links</h2>
                        </div>
                        <div class="list-item" style="border-bottom: none; padding: 1rem 1.5rem;">
                            <div class="list-icon forum">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <div class="list-info">
                                <a href="/forum/index.php" class="list-title">Community Forum</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
