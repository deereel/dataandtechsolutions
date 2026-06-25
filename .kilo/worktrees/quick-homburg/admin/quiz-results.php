<?php
/**
 * Data Tutors - Quiz Results Detail Page
 * Displays detailed quiz results including questions and answers
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - only admins can access
if (!isAdminLoggedIn() || !isAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get quiz result ID from URL
$resultId = intval($_GET['id'] ?? 0);
if ($resultId <= 0) {
    redirect(APP_URL . '/admin/users.php');
}

// Get detailed quiz result
$quizResult = Quiz::getResultWithAnswers($resultId);
if (!$quizResult) {
    redirect(APP_URL . '/admin/users.php');
}

// Get student information
$student = User::getById($quizResult['user_id']);

// Get course information
$course = DatabaseConnection::fetch("SELECT * FROM courses WHERE id = ?", [$quizResult['quiz']['course_id']]);

// Calculate grade breakdown
$correctAnswers = count(array_filter($quizResult['questions'], function($q) {
    return $q['is_correct'];
}));
$totalQuestions = count($quizResult['questions']);
$accuracy = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

define('PAGE_TITLE', 'Quiz Results - ' . $student['name']);
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
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
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
        .card-body {
            padding: 1.5rem;
        }
        .question-item {
            background: var(--gray-50);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .question-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .question-number {
            font-weight: 700;
            color: var(--primary);
            min-width: 30px;
        }
        .question-text {
            flex: 1;
            font-weight: 500;
        }
        .question-score {
            color: var(--gray-600);
            font-size: 0.875rem;
        }
        .options-list {
            margin-top: 1rem;
            list-style: none;
            padding-left: 0;
        }
        .option-item {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: var(--transition);
        }
        .option-item:hover {
            border-color: var(--gray-300);
        }
        .option-letter {
            font-weight: 700;
            color: var(--primary);
            min-width: 20px;
        }
        .option-text {
            flex: 1;
        }
        .option-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .option-correct {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.4);
        }
        .option-correct .option-indicator {
            background: #059669;
            color: white;
        }
        .option-incorrect {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
        }
        .option-incorrect .option-indicator {
            background: #dc2626;
            color: white;
        }
        .option-selected {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        .question-explanation {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--yellow-50);
            border-radius: var(--radius);
            color: var(--yellow-800);
            font-size: 0.875rem;
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
                    <h1>Quiz Results</h1>
                    <p style="color: var(--gray-500);">View detailed quiz answers and grading</p>
                </div>
                <a href="<?= APP_URL ?>/admin/student-detail.php?id=<?= $student['id'] ?>" class="btn btn-secondary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Student
                </a>
            </div>
            
            <!-- Student and Course Info -->
            <div class="admin-card">
                <div class="card-header">
                    <h2 class="card-title">Quiz Information</h2>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div>
                            <strong>Student:</strong> <?= sanitize($student['name']) ?>
                        </div>
                        <div>
                            <strong>Email:</strong> <?= sanitize($student['email']) ?>
                        </div>
                        <div>
                            <strong>Course:</strong> <?= sanitize($course['title']) ?>
                        </div>
                        <div>
                            <strong>Quiz:</strong> <?= sanitize($quizResult['quiz']['title']) ?>
                        </div>
                        <div>
                            <strong>Date:</strong> <?= formatDate($quizResult['completed_at']) ?>
                        </div>
                        <div>
                            <strong>Time Taken:</strong> <?= round($quizResult['time_taken'] / 60) ?> minutes
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quiz Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $quizResult['score'] ?>/<?= $quizResult['total_points'] ?></div>
                    <div class="stat-label">Score</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= round($quizResult['percentage']) ?>%</div>
                    <div class="stat-label">Percentage</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $correctAnswers ?>/<?= $totalQuestions ?></div>
                    <div class="stat-label">Correct Answers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= round($accuracy) ?>%</div>
                    <div class="stat-label">Accuracy</div>
                </div>
            </div>
            
            <!-- Quiz Questions and Answers -->
            <div class="admin-card">
                <div class="card-header">
                    <h2 class="card-title">Questions and Answers</h2>
                </div>
                <div class="card-body">
                    <?php foreach ($quizResult['questions'] as $index => $question): ?>
                    <div class="question-item">
                        <div class="question-header">
                            <span class="question-number"><?= $index + 1 ?></span>
                            <div class="question-text">
                                <?= sanitize($question['question_text']) ?>
                                <div class="question-score">
                                    Points: <?= $question['points'] ?>
                                </div>
                            </div>
                        </div>
                        
                        <ul class="options-list">
                            <?php foreach ($question['options'] as $optionIndex => $option): ?>
                            <li class="option-item
                                <?= $option['is_correct'] ? 'option-correct' : '' ?>
                                <?= $question['user_answer'] == $option['id'] ? 'option-selected' : '' ?>
                                <?= $question['user_answer'] == $option['id'] && !$option['is_correct'] ? 'option-incorrect' : '' ?>">
                                <span class="option-letter"><?= chr(65 + $optionIndex) ?></span>
                                <span class="option-text"><?= sanitize($option['option_text']) ?></span>
                                <div class="option-indicator">
                                    <?php if ($option['is_correct']): ?>
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <?php elseif ($question['user_answer'] == $option['id']): ?>
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <?php if ($question['explanation']): ?>
                        <div class="question-explanation">
                            <strong>Explanation:</strong> <?= sanitize($question['explanation']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
