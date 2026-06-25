<?php
/**
 * Data Tutors - Admin Forum Moderation
 * Moderate forum questions and discussions
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - admins and tutors can access
if (!isAdminLoggedIn() || !isAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get current admin user
$currentAdmin = getCurrentAdmin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdminUser()) {
    $action = $_POST['action'] ?? '';
    
    // Toggle question visibility
    if ($action === 'toggle_visibility') {
        $questionId = intval($_POST['question_id'] ?? 0);
        
        if ($questionId > 0) {
            $question = DatabaseConnection::fetch("SELECT * FROM forum_questions WHERE id = ?", [$questionId]);
            if ($question) {
                $newVisibility = $question['is_deleted'] ? 0 : 1;
                DatabaseConnection::query(
                    "UPDATE forum_questions SET is_deleted = ? WHERE id = ?",
                    [$newVisibility, $questionId]
                );
                logActivity($_SESSION['admin_id'], 'toggle_forum_visibility', 'forum_questions', $questionId, null, [
                    'is_deleted' => $newVisibility
                ]);
                $success = $newVisibility ? 'Question restored successfully.' : 'Question hidden successfully.';
            }
        }
    }
    
    // Delete question
    elseif ($action === 'delete') {
        $questionId = intval($_POST['question_id'] ?? 0);
        
        if ($questionId > 0) {
            $question = DatabaseConnection::fetch("SELECT * FROM forum_questions WHERE id = ?", [$questionId]);
            if ($question) {
                DatabaseConnection::query("DELETE FROM forum_questions WHERE id = ?", [$questionId]);
                logActivity($_SESSION['admin_id'], 'delete_forum_question', 'forum_questions', $questionId, $question, null);
                $success = 'Question deleted successfully.';
            }
        }
    }
    
    // Toggle answer visibility
    elseif ($action === 'toggle_answer') {
        $answerId = intval($_POST['answer_id'] ?? 0);
        
        if ($answerId > 0) {
            $answer = DatabaseConnection::fetch("SELECT * FROM forum_answers WHERE id = ?", [$answerId]);
            if ($answer) {
                $newVisibility = $answer['is_deleted'] ? 0 : 1;
                DatabaseConnection::query(
                    "UPDATE forum_answers SET is_deleted = ? WHERE id = ?",
                    [$newVisibility, $answerId]
                );
                $success = $newVisibility ? 'Answer restored successfully.' : 'Answer hidden successfully.';
            }
        }
    }
}

// Get forum statistics
$stats = [
    'total_questions' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_questions"),
    'active_questions' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_questions WHERE is_deleted = 0"),
    'total_answers' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_answers"),
    'pending_answers' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM forum_answers WHERE is_deleted = 0 AND is_accepted = 0")
];

// Get recent questions with answers
$recentQuestions = DatabaseConnection::fetchAll("
    SELECT q.*, u.name as author_name, u.email as author_email,
           (SELECT COUNT(*) FROM forum_answers WHERE question_id = q.id AND is_deleted = 0) as answer_count
    FROM forum_questions q
    JOIN users u ON q.user_id = u.id
    WHERE q.is_deleted = 0
    ORDER BY q.created_at DESC
    LIMIT 20
");

define('PAGE_TITLE', 'Forum Moderation');
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
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        .btn-sm {
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
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
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        
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
        
        .question-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .question-item:last-child {
            border-bottom: none;
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .question-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        .question-meta {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .question-excerpt {
            color: var(--gray-600);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .question-stats {
            display: flex;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .question-stats span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
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
                    <h1>Forum Moderation</h1>
                    <p style="color: var(--gray-500);">Review and moderate forum discussions</p>
                </div>
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
                    <div class="stat-value"><?= $stats['total_questions'] ?></div>
                    <div class="stat-label">Total Questions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['active_questions'] ?></div>
                    <div class="stat-label">Active Questions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_answers'] ?></div>
                    <div class="stat-label">Total Answers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['pending_answers'] ?></div>
                    <div class="stat-label">Pending Answers</div>
                </div>
            </div>
            
            <!-- Recent Questions -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Questions</h3>
                    <span style="color: var(--gray-500); font-size: 0.875rem;">
                        <?= count($recentQuestions) ?> questions
                    </span>
                </div>
                
                <?php if (empty($recentQuestions)): ?>
                    <div style="padding: 3rem; text-align: center; color: var(--gray-500);">
                        No questions found.
                    </div>
                <?php else: ?>
                    <?php foreach ($recentQuestions as $question): ?>
                    <div class="question-item">
                        <div class="question-header">
                            <div>
                                <div class="question-title"><?= sanitize($question['title']) ?></div>
                                <div class="question-meta">
                                    Posted by <?= sanitize($question['author_name']) ?> • <?= timeAgo($question['created_at']) ?>
                                    <?php if ($question['course_id']): ?>
                                        • In course #<?= $question['course_id'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (isAdminUser()): ?>
                            <div class="actions">
                                <a href="/forum/question.php?id=<?= $question['id'] ?>" class="btn btn-secondary btn-sm" target="_blank">
                                    View
                                </a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_visibility">
                                    <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                                    <button type="submit" class="btn btn-<?= $question['is_deleted'] ? 'success' : 'warning' ?> btn-sm">
                                        <?= $question['is_deleted'] ? 'Restore' : 'Hide' ?>
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="question-excerpt">
                            <?= substr(strip_tags($question['content']), 0, 200) ?><?= strlen(strip_tags($question['content'])) > 200 ? '...' : '' ?>
                        </div>
                        <div class="question-stats">
                            <span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <?= $question['answer_count'] ?> answers
                            </span>
                            <span>
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <?= $question['views'] ?? 0 ?> views
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
