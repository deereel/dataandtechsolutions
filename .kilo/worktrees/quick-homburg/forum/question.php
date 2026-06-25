<?php
/**
 * Data Tutors - Forum Question Detail
 * View and answer forum questions
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check for posting answers
$userId = isLoggedIn() ? $_SESSION['user_id'] : 0;

// Get question ID
$questionId = intval($_GET['id'] ?? 0);

if (!$questionId) {
    redirect(APP_URL . '/forum/index.php');
}

// Fetch question
$question = Forum::getQuestionById($questionId);
if (!$question) {
    redirect(APP_URL . '/forum/index.php');
}

// Increment view count
DatabaseConnection::query("UPDATE forum_questions SET views = views + 1 WHERE id = ?", [$questionId]);

// Fetch answers
$answers = Forum::getAnswers($questionId);

// Handle new answer submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'post_answer') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect(APP_URL . '/auth/login.php');
    }
    
    $content = sanitize($_POST['content'] ?? '');
    
    if (empty($content)) {
        $error = 'Please write your answer.';
    } elseif (strlen($content) < 10) {
        $error = 'Your answer must be at least 10 characters.';
    } else {
        Forum::createAnswer($userId, [
            'question_id' => $questionId,
            'content' => $content,
            'parent_answer_id' => null
        ]);
        
        $_SESSION['success'] = 'Your answer has been posted!';
        redirect(APP_URL . '/forum/question.php?id=' . $questionId);
    }
}

// Handle accept answer (if question author)
if (isset($_POST['action']) && $_POST['action'] === 'accept_answer' && isLoggedIn()) {
    if ($question['user_id'] == $userId || isAdmin()) {
        $answerId = intval($_POST['answer_id'] ?? 0);
        DatabaseConnection::query("UPDATE forum_answers SET is_accepted = 0 WHERE question_id = ?", [$questionId]);
        DatabaseConnection::query("UPDATE forum_answers SET is_accepted = 1 WHERE id = ?", [$answerId]);
        DatabaseConnection::query("UPDATE forum_questions SET is_resolved = 1 WHERE id = ?", [$questionId]);
        $_SESSION['success'] = 'Answer accepted!';
        redirect(APP_URL . '/forum/question.php?id=' . $questionId);
    }
}

define('PAGE_TITLE', $question['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .question-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--gray-50);
        }
        .question-header {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid var(--gray-200);
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }
        .breadcrumb a {
            color: var(--primary);
        }
        .question-title {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }
        .question-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
            flex-wrap: wrap;
        }
        .question-body {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
question-content {
                   }
        . background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }
        .question-text {
            line-height: 1.8;
            color: var(--gray-700);
            white-space: pre-wrap;
        }
        .answers-section {
            margin-top: 2rem;
        }
        .answers-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .answers-count {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }
        .answer-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem 2rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }
        .answer-card.accepted {
            border: 2px solid var(--success);
        }
        .answer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .answer-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--gray-600);
        }
        .author-name {
            font-weight: 500;
            color: var(--gray-800);
        }
        .answer-time {
            font-size: 0.8rem;
            color: var(--gray-400);
        }
        .accepted-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .answer-content {
            line-height: 1.8;
            color: var(--gray-700);
            white-space: pre-wrap;
        }
        .answer-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-100);
        }
        .vote-btn {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 0.75rem;
            background: var(--gray-100);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--gray-600);
            border: none;
            cursor: pointer;
        }
        .vote-btn:hover {
            background: var(--gray-200);
        }
        
        /* Answer Form */
        .answer-form {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: var(--shadow);
        }
        .form-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        textarea {
            width: 100%;
            min-height: 150px;
            padding: 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
        }
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .login-prompt {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: var(--radius-lg);
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="question-page">
        <div class="question-header">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/forum/index.php">Forum</a>
                    <span>/</span>
                    <span>Question</span>
                </div>
                <h1 class="question-title"><?= sanitize($question['title']) ?></h1>
                <div class="question-meta">
                    <span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <?= sanitize($question['author_name']) ?>
                    </span>
                    <span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?= timeAgo($question['created_at']) ?>
                    </span>
                    <span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <?= $question['views'] ?> views
                    </span>
                    <?php if ($question['is_resolved']): ?>
                        <span class="accepted-badge">✓ Resolved</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="question-body">
            <!-- Question Content -->
            <div class="question-content">
                <div class="question-text"><?= nl2br(sanitize($question['content'])) ?></div>
            </div>
            
            <!-- Answers -->
            <div class="answers-section">
                <div class="answers-header">
                    <h2 class="answers-count"><?= count($answers) ?> Answer<?= count($answers) != 1 ? 's' : '' ?></h2>
                </div>
                
                <?php if (empty($answers)): ?>
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: var(--radius-lg);">
                        <p style="color: var(--gray-500);">No answers yet. Be the first to help!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($answers as $answer): ?>
                        <div class="answer-card <?= $answer['is_accepted'] ? 'accepted' : '' ?>">
                            <div class="answer-header">
                                <div class="answer-author">
                                    <div class="author-avatar">
                                        <?= strtoupper(substr($answer['author_name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <div class="author-name"><?= sanitize($answer['author_name']) ?></div>
                                        <div class="answer-time"><?= timeAgo($answer['created_at']) ?></div>
                                    </div>
                                </div>
                                <?php if ($answer['is_accepted']): ?>
                                    <span class="accepted-badge">✓ Accepted Answer</span>
                                <?php endif; ?>
                            </div>
                            <div class="answer-content"><?= nl2br(sanitize($answer['content'])) ?></div>
                            <div class="answer-actions">
                                <button class="vote-btn">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    Helpful
                                </button>
                                <?php if (!$answer['is_accepted'] && ($question['user_id'] == $userId || isAdmin())): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="accept_answer">
                                        <input type="hidden" name="answer_id" value="<?= $answer['id'] ?>">
                                        <button type="submit" class="vote-btn" style="color: var(--success);">
                                            ✓ Accept Answer
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Answer Form -->
            <?php if (isLoggedIn()): ?>
                <div class="answer-form">
                    <h3 class="form-title">Your Answer</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= sanitize($error) ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="post_answer">
                        <div class="form-group">
                            <textarea name="content" placeholder="Write your answer here... Be helpful and provide clear explanations." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Post Your Answer</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-prompt">
                    <p style="color: var(--gray-600); margin-bottom: 1rem;">You need to be logged in to post an answer.</p>
                    <a href="/auth/login.php?redirect=/forum/question.php?id=<?= $questionId ?>" class="btn btn-primary">Login to Answer</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
