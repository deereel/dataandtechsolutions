<?php
/**
 * Data Tutors - Forum Index
 * Community Q&A forum
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Get filters
$categoryId = intval($_GET['category'] ?? 0);
$sort = sanitize($_GET['sort'] ?? 'recent');
$resolved = isset($_GET['resolved']) ? '1' : '';

// Build query
$sql = "SELECT fq.*, u.name as author_name, u.avatar as author_avatar,
               (SELECT COUNT(*) FROM forum_answers WHERE question_id = fq.id) as answer_count
        FROM forum_questions fq
        JOIN users u ON fq.user_id = u.id
        WHERE fq.is_deleted = 0";
$params = [];

if ($categoryId > 0) {
    $sql .= " AND fq.category_id = ?";
    $params[] = $categoryId;
}

if ($resolved !== '') {
    $sql .= " AND fq.is_resolved = ?";
    $params[] = $resolved;
}

switch ($sort) {
    case 'popular':
        $sql .= " ORDER BY fq.views DESC, fq.created_at DESC";
        break;
    case 'unanswered':
        $sql .= " HAVING answer_count = 0 ORDER BY fq.created_at DESC";
        break;
    default:
        $sql .= " ORDER BY fq.is_pinned DESC, fq.created_at DESC";
}

$questions = DatabaseConnection::fetchAll($sql, $params);

// Get categories
$categories = Forum::getCategories();

define('PAGE_TITLE', 'Community Forum');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .forum-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--gray-50);
        }
        .forum-header {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid var(--gray-200);
        }
        .forum-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .forum-title h1 {
            font-size: 1.75rem;
            margin-bottom: 0.25rem;
        }
        .forum-title p {
            color: var(--gray-500);
        }
        .btn-ask {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border-radius: var(--radius);
            font-weight: 500;
        }
        .forum-content {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            padding: 2rem 0 4rem;
        }
        .forum-sidebar {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 90px;
        }
        .sidebar-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-800);
        }
        .category-list {
            list-style: none;
        }
        .category-item {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 0.25rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .category-item:hover {
            background: var(--gray-50);
        }
        .category-item.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }
        .category-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        .category-count {
            margin-left: auto;
            font-size: 0.8rem;
            color: var(--gray-400);
        }
        .forum-main {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .forum-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .forum-tabs {
            display: flex;
            gap: 0.5rem;
        }
        .forum-tab {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--gray-600);
            background: none;
            border: none;
            cursor: pointer;
        }
        .forum-tab.active {
            background: var(--primary);
            color: white;
        }
        .sort-select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.875rem;
        }
        .question-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }
        .question-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        .question-card.pinned {
            border: 2px solid var(--warning);
            background: rgba(245, 158, 11, 0.02);
        }
        .question-stats {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 80px;
        }
        .stat-box {
            text-align: center;
            padding: 0.5rem;
            border-radius: var(--radius);
            background: var(--gray-50);
            width: 100%;
        }
        .stat-box.answers {
            background: var(--success);
            color: white;
        }
        .stat-box.answers.zero {
            background: var(--gray-100);
            color: var(--gray-500);
        }
        .stat-number {
            font-weight: 700;
            font-size: 1.25rem;
        }
        .stat-label {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        .question-content {
            flex: 1;
        }
        .question-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
            display: block;
        }
        .question-title:hover {
            color: var(--primary);
        }
        .question-excerpt {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .question-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--gray-500);
            flex-wrap: wrap;
        }
        .meta-tag {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .author-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--gray-200);
        }
        .resolved-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .pinned-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
        }
        
        @media (max-width: 1024px) {
            .forum-content {
                grid-template-columns: 1fr;
            }
            .forum-sidebar {
                position: static;
            }
        }
        @media (max-width: 640px) {
            .question-card {
                flex-direction: column;
            }
            .question-stats {
                flex-direction: row;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="forum-page">
        <div class="forum-header">
            <div class="container">
                <div class="forum-header-content">
                    <div class="forum-title">
                        <h1>Community Forum</h1>
                        <p>Ask questions, share knowledge, and connect with fellow learners</p>
                    </div>
                    <?php if (isLoggedIn()): ?>
                        <a href="/forum/ask.php" class="btn-ask">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ask a Question
                        </a>
                    <?php else: ?>
                        <a href="/auth/login.php?redirect=/forum/ask.php" class="btn-ask">
                            Login to Ask
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="forum-content">
                <!-- Sidebar -->
                <aside class="forum-sidebar">
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="category-list">
                        <li>
                            <a href="/forum/index.php" class="category-item <?= $categoryId == 0 ? 'active' : '' ?>">
                                <span class="category-dot" style="background: var(--gray-400);"></span>
                                <span>All Discussions</span>
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="?category=<?= $cat['id'] ?>" class="category-item <?= $categoryId == $cat['id'] ? 'active' : '' ?>">
                                    <span class="category-dot" style="background: <?= $cat['color'] ?>;"></span>
                                    <span><?= sanitize($cat['name']) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>
                
                <!-- Main Content -->
                <div class="forum-main">
                    <div class="forum-toolbar">
                        <div class="forum-tabs">
                            <a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'recent', 'resolved' => ''])) ?>" 
                               class="forum-tab <?= $sort == 'recent' && $resolved === '' ? 'active' : '' ?>">
                                Recent
                            </a>
                            <a href="?sort=popular" class="forum-tab <?= $sort == 'popular' ? 'active' : '' ?>">
                                Popular
                            </a>
                            <a href="?sort=unanswered" class="forum-tab <?= $sort == 'unanswered' ? 'active' : '' ?>">
                                Unanswered
                            </a>
                        </div>
                        <form method="GET" action="">
                            <?php if ($categoryId): ?>
                                <input type="hidden" name="category" value="<?= $categoryId ?>">
                            <?php endif; ?>
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <option value="recent" <?= $sort == 'recent' ? 'selected' : '' ?>>Most Recent</option>
                                <option value="popular" <?= $sort == 'popular' ? 'selected' : '' ?>>Most Popular</option>
                                <option value="unanswered" <?= $sort == 'unanswered' ? 'selected' : '' ?>>Unanswered</option>
                            </select>
                        </form>
                    </div>
                    
                    <!-- Questions List -->
                    <?php if (empty($questions)): ?>
                        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: var(--radius-lg);">
                            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; color: var(--gray-300);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <h3 style="color: var(--gray-600); margin-bottom: 0.5rem;">No questions yet</h3>
                            <p style="color: var(--gray-500); margin-bottom: 1rem;">Be the first to ask a question!</p>
                            <?php if (isLoggedIn()): ?>
                                <a href="/forum/ask.php" class="btn btn-primary">Ask a Question</a>
                            <?php else: ?>
                                <a href="/auth/login.php?redirect=/forum/ask.php" class="btn btn-primary">Login to Ask</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($questions as $question): ?>
                            <div class="question-card <?= $question['is_pinned'] ? 'pinned' : '' ?>">
                                <div class="question-stats">
                                    <div class="stat-box">
                                        <div class="stat-number"><?= $question['answer_count'] ?></div>
                                        <div class="stat-label">answers</div>
                                    </div>
                                </div>
                                <div class="question-content">
                                    <a href="/forum/question.php?id=<?= $question['id'] ?>" class="question-title">
                                        <?php if ($question['is_pinned']): ?>
                                            <span class="pinned-badge">📌 Pinned</span>
                                        <?php endif; ?>
                                        <?php if ($question['is_resolved']): ?>
                                            <span class="resolved-badge">✓ Resolved</span>
                                        <?php endif; ?>
                                        <?= sanitize($question['title']) ?>
                                    </a>
                                    <p class="question-excerpt"><?= sanitize(substr($question['content'], 0, 200)) ?>...</p>
                                    <div class="question-meta">
                                        <span class="meta-tag">
                                            <span class="author-avatar"></span>
                                            <?= sanitize($question['author_name']) ?>
                                        </span>
                                        <span class="meta-tag">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?= timeAgo($question['created_at']) ?>
                                        </span>
                                        <span class="meta-tag">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <?= $question['views'] ?> views
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
