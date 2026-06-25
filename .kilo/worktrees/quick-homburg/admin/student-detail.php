<?php
/**
 * Data Tutors - Student Detail Page
 * Comprehensive student management page with detailed information and operations
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

// Get student ID from URL
$studentId = intval($_GET['id'] ?? 0);
if ($studentId <= 0) {
    redirect(APP_URL . '/admin/users.php');
}

// Get student information
$student = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ? AND role = 'student'", [$studentId]);
if (!$student) {
    redirect(APP_URL . '/admin/users.php');
}

// Handle form submissions
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Update student information
    if ($action === 'update_student' && isSuperAdmin()) {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($name) || empty($email)) {
            $error = 'Please fill in all required fields.';
        } else {
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
                User::update($studentId, $updateData);
                logActivity($_SESSION['admin_id'], 'update_student_detail', 'users', $studentId, $student, $updateData);
                $success = 'Student information updated successfully.';
                // Refresh student data
                $student = DatabaseConnection::fetch("SELECT * FROM users WHERE id = ? AND role = 'student'", [$studentId]);
            }
        }
    }
    
    // Enroll student in course
    if ($action === 'enroll_course' && isSuperAdmin()) {
        $courseId = intval($_POST['course_id'] ?? 0);
        if ($courseId > 0) {
            // Check if already enrolled
            $existing = DatabaseConnection::fetch("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?", [$studentId, $courseId]);
            if (!$existing) {
                DatabaseConnection::insert('enrollments', [
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'enrolled_at' => date('Y-m-d H:i:s')
                ]);
                
                // Create progress record
                $totalLessons = DatabaseConnection::fetch("SELECT COUNT(*) as count FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = ?)", [$courseId]);
                DatabaseConnection::insert('user_course_progress', [
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'total_lessons' => $totalLessons['count'],
                    'total_lessons_completed' => 0,
                    'progress_percentage' => 0.00
                ]);
                
                logActivity($_SESSION['admin_id'], 'enroll_student', 'enrollments', $courseId, null, [
                    'student_id' => $studentId,
                    'course_id' => $courseId
                ]);
                $success = 'Student enrolled successfully.';
            } else {
                $error = 'Student is already enrolled in this course.';
            }
        }
    }
    
    // Unenroll student from course
    if ($action === 'unenroll_course' && isSuperAdmin()) {
        $courseId = intval($_POST['course_id'] ?? 0);
        if ($courseId > 0) {
            DatabaseConnection::delete('enrollments', 'user_id = :userId AND course_id = :courseId', ['userId' => $studentId, 'courseId' => $courseId]);
            DatabaseConnection::delete('user_course_progress', 'user_id = :userId AND course_id = :courseId', ['userId' => $studentId, 'courseId' => $courseId]);
            DatabaseConnection::delete('user_lesson_progress', 'user_id = :userId AND lesson_id IN (SELECT id FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = :courseId))', ['userId' => $studentId, 'courseId' => $courseId]);
            DatabaseConnection::delete('quiz_results', 'user_id = :userId AND quiz_id IN (SELECT id FROM quizzes WHERE course_id = :courseId)', ['userId' => $studentId, 'courseId' => $courseId]);
            
            logActivity($_SESSION['admin_id'], 'unenroll_student', 'enrollments', $courseId, null, [
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
            $success = 'Student unenrolled successfully.';
        }
    }
    
    // Reset course progress
    if ($action === 'reset_progress' && isSuperAdmin()) {
        $courseId = intval($_POST['course_id'] ?? 0);
        if ($courseId > 0) {
            $totalLessons = DatabaseConnection::fetch("SELECT COUNT(*) as count FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = ?)", [$courseId]);
            DatabaseConnection::update('user_course_progress', 
                ['total_lessons_completed' => 0, 'progress_percentage' => 0.00, 'last_lesson_id' => null], 
                'user_id = :userId AND course_id = :courseId', 
                ['userId' => $studentId, 'courseId' => $courseId]
            );
            DatabaseConnection::delete('user_lesson_progress', 'user_id = :userId AND lesson_id IN (SELECT id FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = :courseId))', ['userId' => $studentId, 'courseId' => $courseId]);
            DatabaseConnection::delete('quiz_results', 'user_id = :userId AND quiz_id IN (SELECT id FROM quizzes WHERE course_id = :courseId)', ['userId' => $studentId, 'courseId' => $courseId]);
            
            logActivity($_SESSION['admin_id'], 'reset_course_progress', 'user_course_progress', $courseId, null, [
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
            $success = 'Course progress reset successfully.';
        }
    }
    
    // Update quiz score
    if ($action === 'update_quiz_score' && isSuperAdmin()) {
        $resultId = intval($_POST['result_id'] ?? 0);
        $newScore = floatval($_POST['score'] ?? 0);
        $newPercentage = floatval($_POST['percentage'] ?? 0);
        $newPassed = intval($_POST['passed'] ?? 0);
        
        if ($resultId > 0 && $newScore >= 0 && $newPercentage >= 0 && $newPercentage <= 100) {
            $result = Quiz::getResultById($resultId);
            if ($result) {
                Quiz::updateResult($resultId, [
                    'score' => $newScore,
                    'percentage' => $newPercentage,
                    'passed' => $newPassed
                ]);
                
                logActivity($_SESSION['admin_id'], 'update_quiz_score', 'quiz_results', $resultId, $result, [
                    'score' => $newScore,
                    'percentage' => $newPercentage,
                    'passed' => $newPassed
                ]);
                
                $success = 'Quiz score updated successfully.';
                // Refresh quiz results
                $quizResults = DatabaseConnection::fetchAll(
                    "SELECT qr.*, q.title as quiz_title, c.title as course_title
                     FROM quiz_results qr
                     JOIN quizzes q ON qr.quiz_id = q.id
                     JOIN courses c ON q.course_id = c.id
                     WHERE qr.user_id = ?
                     ORDER BY qr.completed_at DESC",
                    [$studentId]
                );
            } else {
                $error = 'Quiz result not found.';
            }
        }
    }
}

// Get all courses
$allCourses = DatabaseConnection::fetchAll("SELECT * FROM courses WHERE published = 1 ORDER BY title");

// Get enrolled courses
$enrolledCourses = DatabaseConnection::fetchAll(
    "SELECT c.*, e.enrolled_at, e.completed_at, e.certificate_issued, 
            p.progress_percentage, p.total_lessons_completed, p.total_lessons
     FROM enrollments e
     JOIN courses c ON e.course_id = c.id
     LEFT JOIN user_course_progress p ON e.user_id = p.user_id AND e.course_id = p.course_id
     WHERE e.user_id = ?
     ORDER BY e.enrolled_at DESC",
    [$studentId]
);

// Get quiz results
$quizResults = DatabaseConnection::fetchAll(
    "SELECT qr.*, q.title as quiz_title, c.title as course_title
     FROM quiz_results qr
     JOIN quizzes q ON qr.quiz_id = q.id
     JOIN courses c ON q.course_id = c.id
     WHERE qr.user_id = ?
     ORDER BY qr.completed_at DESC",
    [$studentId]
);

define('PAGE_TITLE', 'Student Detail - ' . $student['name']);
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: 500;
            color: var(--gray-900);
            font-size: 1.25rem;
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
        
        /* Progress bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--gray-200);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        .progress-fill {
            height: 100%;
            background: var(--primary);
            transition: width 0.3s ease;
        }
        .progress-text {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            text-align: center;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
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
                    <h1>Student Detail</h1>
                    <p style="color: var(--gray-500);">Manage student information and course access</p>
                </div>
                <a href="<?= APP_URL ?>/admin/users.php" class="btn btn-secondary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Students
                </a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <!-- Student Overview -->
            <div class="admin-card">
                <div class="card-header">
                    <h2 class="card-title">Student Information</h2>
                    <button class="btn btn-primary" onclick="openEditModal()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Student
                    </button>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="user-info">
                        <div class="user-avatar"><?= strtoupper(substr($student['name'], 0, 2)) ?></div>
                        <div class="user-details">
                            <span class="user-name"><?= sanitize($student['name']) ?></span>
                            <span class="user-email"><?= sanitize($student['email']) ?></span>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">
                                Phone: <?= sanitize($student['phone'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">Status</div>
                            <span class="badge <?= $student['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                <?= ucfirst($student['status']) ?>
                            </span>
                        </div>
                        <div>
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">Joined</div>
                            <div style="color: var(--gray-600);"><?= formatDate($student['created_at']) ?></div>
                        </div>
                        <div>
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">Last Login</div>
                            <div style="color: var(--gray-600);">
                                <?= $student['last_login'] ? formatDate($student['last_login']) : 'Never' ?>
                            </div>
                        </div>
                        <div>
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">Email Verified</div>
                            <span class="badge <?= $student['email_verified'] ? 'badge-success' : 'badge-warning' ?>">
                                <?= $student['email_verified'] ? 'Verified' : 'Not Verified' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= count($enrolledCourses) ?></div>
                    <div class="stat-label">Enrolled Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= count($quizResults) ?></div>
                    <div class="stat-label">Quiz Attempts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?= count(array_filter($enrolledCourses, function($course) {
                            return $course['certificate_issued'] == 1;
                        })) ?>
                    </div>
                    <div class="stat-label">Certificates</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $totalProgress = 0;
                        $completedCourses = 0;
                        foreach ($enrolledCourses as $course) {
                            if ($course['total_lessons'] > 0) {
                                $totalProgress += $course['progress_percentage'];
                            }
                            if ($course['completed_at']) {
                                $completedCourses++;
                            }
                        }
                        echo count($enrolledCourses) > 0 ? round($totalProgress / count($enrolledCourses)) . '%' : '0%';
                        ?>
                    </div>
                    <div class="stat-label">Average Progress</div>
                </div>
            </div>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('courses')">
                    Enrolled Courses (<?= count($enrolledCourses) ?>)
                </button>
                <button class="tab" onclick="switchTab('quizzes')">
                    Quiz Results (<?= count($quizResults) ?>)
                </button>
                <button class="tab" onclick="switchTab('certificates')">
                    Certificates (<?= count(array_filter($enrolledCourses, function($c) { return $c['certificate_issued']; })) ?>)
                </button>
            </div>
            
            <!-- Enrolled Courses Tab -->
            <div id="courses-tab" class="tab-content active">
                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Enrolled Courses</h2>
                        <button class="btn btn-primary" onclick="openEnrollModal()">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Enroll in Course
                        </button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Enrolled</th>
                                    <th>Completed</th>
                                    <th>Certificate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($enrolledCourses as $course): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; flex-direction: column;">
                                            <div style="font-weight: 500; color: var(--gray-900);"><?= sanitize($course['title']) ?></div>
                                            <div style="font-size: 0.875rem; color: var(--gray-500);"><?= sanitize($course['category']) ?> • <?= ucfirst($course['level']) ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?= $course['progress_percentage'] ?>%"></div>
                                        </div>
                                        <div class="progress-text">
                                            <?= round($course['progress_percentage']) ?>% (<?= $course['total_lessons_completed'] ?>/<?= $course['total_lessons'] ?> lessons)
                                        </div>
                                    </td>
                                    <td><?= formatDate($course['enrolled_at']) ?></td>
                                    <td>
                                        <?= $course['completed_at'] ? formatDate($course['completed_at']) : 'Not completed' ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $course['certificate_issued'] ? 'badge-success' : 'badge-secondary' ?>">
                                            <?= $course['certificate_issued'] ? 'Issued' : 'Not issued' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-secondary btn-sm" onclick="resetProgress(<?= $course['id'] ?>)">
                                                Reset Progress
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="unenrollCourse(<?= $course['id'] ?>)">
                                                Unenroll
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Quiz Results Tab -->
            <div id="quizzes-tab" class="tab-content">
                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Quiz Results</h2>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Quiz</th>
                                    <th>Score</th>
                                    <th>Passed</th>
                                    <th>Attempt</th>
                                    <th>Completed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quizResults as $result): ?>
                                <tr>
                                    <td><?= sanitize($result['course_title']) ?></td>
                                    <td><?= sanitize($result['quiz_title']) ?></td>
                                    <td>
                                        <div style="font-weight: 500; color: <?= $result['percentage'] >= 70 ? '#059669' : '#dc2626' ?>">
                                            <?= $result['score'] ?>/<?= $result['total_points'] ?> (<?= round($result['percentage']) ?>%)
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $result['passed'] ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $result['passed'] ? 'Passed' : 'Failed' ?>
                                        </span>
                                    </td>
                                    <td>Attempt <?= $result['attempt_number'] ?></td>
                                    <td><?= formatDate($result['completed_at']) ?></td>
                                    <td>
                                        <div class="actions">
                                            <button class="btn btn-primary btn-sm" onclick="openGradeModal(<?= $result['id'] ?>, <?= $result['score'] ?>, <?= $result['percentage'] ?>, <?= $result['total_points'] ?>, <?= $result['passed'] ?>)">
                                                Grade
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="viewQuizDetails(<?= $result['id'] ?>)">
                                                View Details
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Certificates Tab -->
            <div id="certificates-tab" class="tab-content">
                <div class="admin-card">
                    <div class="card-header">
                        <h2 class="card-title">Certificates</h2>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <?php $certificateCourses = array_filter($enrolledCourses, function($course) {
                            return $course['certificate_issued'];
                        }); ?>
                        
                        <?php if (count($certificateCourses) > 0): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                                <?php foreach ($certificateCourses as $course): ?>
                                    <div style="background: white; border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 1.5rem; text-align: center;">
                                        <div style="font-weight: 500; color: var(--gray-900); margin-bottom: 0.5rem;"><?= sanitize($course['title']) ?></div>
                                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem;">
                                            Completed: <?= formatDate($course['completed_at']) ?>
                                        </div>
                                        <a href="<?= APP_URL ?>/certificate.php?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                            View Certificate
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 3rem;">
                                <div style="font-size: 1.25rem; color: var(--gray-600); margin-bottom: 0.5rem;">No certificates issued</div>
                                <div style="color: var(--gray-500);">This student hasn't completed any courses yet</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Edit Student Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Student Information</h2>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <form method="POST" id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_student">
                    
                    <div class="form-group">
                        <label for="editName" class="form-label">Full Name *</label>
                        <input type="text" id="editName" name="name" class="form-input" value="<?= sanitize($student['name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editEmail" class="form-label">Email Address *</label>
                        <input type="email" id="editEmail" name="email" class="form-input" value="<?= sanitize($student['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="password" id="editPassword" name="password" class="form-input" placeholder="Leave blank to keep current">
                        <small style="color: var(--gray-500); font-size: 0.75rem;">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="editPhone" class="form-label">Phone Number</label>
                        <input type="tel" id="editPhone" name="phone" class="form-input" value="<?= sanitize($student['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
                    </div>
                    
                    <div class="form-group">
                        <label for="editStatus" class="form-label">Status</label>
                        <select id="editStatus" name="status" class="form-select">
                            <option value="active" <?= $student['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $student['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="blocked" <?= $student['status'] === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Enroll Course Modal -->
    <div class="modal" id="enrollModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Enroll Student in Course</h2>
                <button class="modal-close" onclick="closeEnrollModal()">&times;</button>
            </div>
            <form method="POST" id="enrollForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="enroll_course">
                    
                    <div class="form-group">
                        <label for="courseSelect" class="form-label">Select Course *</label>
                        <select id="courseSelect" name="course_id" class="form-select" required>
                            <option value="">-- Choose a course --</option>
                            <?php foreach ($allCourses as $course): ?>
                                <?php $isEnrolled = in_array($course['id'], array_column($enrolledCourses, 'id')); ?>
                                <?php if (!$isEnrolled): ?>
                                    <option value="<?= $course['id'] ?>"><?= sanitize($course['title']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEnrollModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll Student</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Unenroll Course Modal -->
    <div class="modal" id="unenrollModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Unenrollment</h2>
                <button class="modal-close" onclick="closeUnenrollModal()">&times;</button>
            </div>
            <form method="POST" id="unenrollForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="unenroll_course">
                    <input type="hidden" name="course_id" id="unenrollCourseId" value="">
                    <p>Are you sure you want to unenroll this student from the course? This will remove all their progress and quiz results.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeUnenrollModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Unenroll</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Reset Progress Modal -->
    <div class="modal" id="resetProgressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Reset Course Progress</h2>
                <button class="modal-close" onclick="closeResetProgressModal()">&times;</button>
            </div>
            <form method="POST" id="resetProgressForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="reset_progress">
                    <input type="hidden" name="course_id" id="resetCourseId" value="">
                    <p>Are you sure you want to reset this student's progress for this course? This will remove all completed lessons and quiz results.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeResetProgressModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reset Progress</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Grade Quiz Modal -->
    <div class="modal" id="gradeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Grade Quiz</h2>
                <button class="modal-close" onclick="closeGradeModal()">&times;</button>
            </div>
            <form method="POST" id="gradeForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_quiz_score">
                    <input type="hidden" name="result_id" id="gradeResultId" value="">
                    
                    <div class="form-group">
                        <label for="gradeScore" class="form-label">Score</label>
                        <input type="number" id="gradeScore" name="score" class="form-input" min="0" step="0.5" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradePercentage" class="form-label">Percentage (%)</label>
                        <input type="number" id="gradePercentage" name="percentage" class="form-input" min="0" max="100" step="0.1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradePassed" class="form-label">Passed</label>
                        <select id="gradePassed" name="passed" class="form-select" required>
                            <option value="1">Passed</option>
                            <option value="0">Failed</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeGradeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            document.querySelector(`.tab[onclick="switchTab('${tab}')"]`).classList.add('active');
            document.getElementById(`${tab}-tab`).classList.add('active');
        }
        
        // Edit Modal
        function openEditModal() {
            document.getElementById('editModal').classList.add('active');
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }
        
        // Enroll Modal
        function openEnrollModal() {
            document.getElementById('enrollModal').classList.add('active');
        }
        
        function closeEnrollModal() {
            document.getElementById('enrollModal').classList.remove('active');
        }
        
        // Unenroll Modal
        function unenrollCourse(courseId) {
            document.getElementById('unenrollCourseId').value = courseId;
            document.getElementById('unenrollModal').classList.add('active');
        }
        
        function closeUnenrollModal() {
            document.getElementById('unenrollModal').classList.remove('active');
        }
        
        // Reset Progress Modal
        function resetProgress(courseId) {
            document.getElementById('resetCourseId').value = courseId;
            document.getElementById('resetProgressModal').classList.add('active');
        }
        
        function closeResetProgressModal() {
            document.getElementById('resetProgressModal').classList.remove('active');
        }
        
        // Grade Modal
        function openGradeModal(resultId, score, percentage, totalPoints, passed) {
            document.getElementById('gradeResultId').value = resultId;
            document.getElementById('gradeScore').value = score;
            document.getElementById('gradePercentage').value = percentage;
            document.getElementById('gradePassed').value = passed;
            document.getElementById('gradeModal').classList.add('active');
        }
        
        function closeGradeModal() {
            document.getElementById('gradeModal').classList.remove('active');
        }
        
        // View Quiz Details
        function viewQuizDetails(resultId) {
            window.location.href = '<?= APP_URL ?>/admin/quiz-results.php?id=' + resultId;
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