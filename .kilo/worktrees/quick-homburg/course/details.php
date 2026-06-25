<?php
/**
 * Data Tutors - Course Details Page
 * Individual course page with curriculum and enrollment
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Get course ID or slug
$courseId = intval($_GET['id'] ?? 0);
$slug = sanitize($_GET['slug'] ?? '');

// Fetch course
if ($courseId > 0) {
    $course = Course::getById($courseId);
} elseif (!empty($slug)) {
    $course = Course::getBySlug($slug);
}

if (!$course) {
    redirect(APP_URL . '/course/index.php');
}

$courseId = $course['id'];

// Get course modules and lessons
$modules = Course::getModules($courseId);

// Get enrollment status
$isEnrolled = false;
$progress = null;
if (isLoggedIn()) {
    $isEnrolled = Enrollment::isEnrolled($_SESSION['user_id'], $courseId);
    if ($isEnrolled) {
        $progress = DatabaseConnection::fetch(
            "SELECT * FROM user_course_progress WHERE user_id = ? AND course_id = ?",
            [$_SESSION['user_id'], $courseId]
        );
    }
}

// Get lesson count
$lessonCount = Course::getLessonCount($courseId);

define('PAGE_TITLE', $course['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .course-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: white;
            padding: 8rem 0 4rem;
            margin-top: 70px;
        }
        .course-header-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 4rem;
            align-items: start;
        }
        .course-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            opacity: 0.8;
        }
        .course-breadcrumb a {
            color: white;
            opacity: 0.8;
        }
        .course-breadcrumb a:hover {
            opacity: 1;
        }
        .course-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: white;
        }
        .course-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            opacity: 0.9;
        }
        .course-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .course-description {
            font-size: 1.125rem;
            opacity: 0.9;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .course-badges {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .course-badge {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .enrollment-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-xl);
            color: var(--gray-800);
        }
        .enrollment-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        .enrollment-price .free {
            color: var(--success);
        }
        .enrollment-price small {
            font-size: 1rem;
            font-weight: 400;
            color: var(--gray-500);
        }
        .enrollment-features {
            list-style: none;
            margin: 1.5rem 0;
        }
        .enrollment-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            color: var(--gray-600);
        }
        .enrollment-features li::before {
            content: '✓';
            color: var(--success);
            font-weight: 700;
        }
        .progress-info {
            background: var(--gray-50);
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        .btn-enroll {
            width: 100%;
            padding: 1rem;
            font-size: 1.125rem;
        }
        .btn-resume {
            width: 100%;
            padding: 1rem;
            font-size: 1.125rem;
            background: var(--success);
        }
        
        /* Curriculum Section */
        .course-content-section {
            padding: 4rem 0;
        }
        .section-title {
            font-size: 1.75rem;
            margin-bottom: 2rem;
        }
        .curriculum-container {
            max-width: 800px;
        }
        .module-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .module-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            transition: var(--transition);
        }
        .module-header:hover {
            background: var(--gray-50);
        }
        .module-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .module-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .module-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
        }
        .module-meta {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .module-toggle {
            color: var(--gray-400);
            transition: var(--transition);
        }
        .module-toggle.rotated {
            transform: rotate(180deg);
        }
        .lessons-list {
            display: none;
            border-top: 1px solid var(--gray-100);
        }
        .lessons-list.expanded {
            display: block;
        }
        .lesson-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition);
        }
        .lesson-item:last-child {
            border-bottom: none;
        }
        .lesson-item:hover {
            background: var(--gray-50);
        }
        .lesson-item.completed .lesson-icon {
            background: var(--success);
            color: white;
        }
        .lesson-icon {
            width: 32px;
            height: 32px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            flex-shrink: 0;
        }
        .lesson-info {
            flex: 1;
        }
        .lesson-title {
            font-weight: 500;
            color: var(--gray-800);
        }
        .lesson-meta {
            font-size: 0.8rem;
            color: var(--gray-500);
            display: flex;
            gap: 1rem;
        }
        .lesson-link {
            color: var(--primary);
            font-weight: 500;
            font-size: 0.875rem;
        }
        .lesson-link.locked {
            color: var(--gray-400);
            pointer-events: none;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .course-header-content {
                grid-template-columns: 1fr;
            }
            .enrollment-card {
                order: -1;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="course-header">
        <div class="container">
            <div class="course-header-content">
                <div>
                    <div class="course-breadcrumb">
                        <a href="/">Home</a>
                        <span>/</span>
                        <a href="/course/index.php">Courses</a>
                        <span>/</span>
                        <span><?= sanitize($course['category']) ?></span>
                    </div>
                    <h1 class="course-title"><?= sanitize($course['title']) ?></h1>
                    <div class="course-meta">
                        <span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?= $course['duration_hours'] ?: 'N/A' ?> hours
                        </span>
                        <span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?= ucfirst($course['level']) ?> Level
                        </span>
                        <span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <?= $lessonCount ?> Lessons
                        </span>
                    </div>
                    <p class="course-description"><?= sanitize($course['description']) ?></p>
                    <div class="course-badges">
                        <span class="course-badge">✓ Certificate</span>
                        <span class="course-badge">✓ Lifetime Access</span>
                        <span class="course-badge">✓ Quiz Included</span>
                    </div>
                </div>
                
                <div class="enrollment-card">
                    <?php if ($isEnrolled && $progress): ?>
                        <div class="progress-info">
                            <div class="progress-label">
                                <span>Your Progress</span>
                                <span><?= number_format($progress['progress_percentage'], 0) ?>%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= $progress['progress_percentage'] ?>%"></div>
                            </div>
                            <div style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--gray-500);">
                                <?= $progress['total_lessons_completed'] ?> of <?= $progress['total_lessons'] ?> lessons completed
                            </div>
                        </div>
                        <a href="/course/lesson.php?course_id=<?= $courseId ?>&lesson_id=<?= $progress['last_lesson_id'] ?: 0 ?>" class="btn btn-resume btn-lg">
                            Resume Learning
                        </a>
                    <?php elseif ($course['price'] == 0): ?>
                        <div class="enrollment-price free">Free</div>
                        <ul class="enrollment-features">
                            <li>Full course access</li>
                            <li>Lifetime access</li>
                            <li>Certificate on completion</li>
                            <li>Access to community forum</li>
                        </ul>
                        <?php if (isLoggedIn()): ?>
                            <form method="POST" action="/payments/enroll.php">
                                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                <button type="submit" class="btn btn-primary btn-enroll btn-lg">Enroll Now - Free</button>
                            </form>
                        <?php else: ?>
                            <a href="/auth/login.php?redirect=/course/details.php?id=<?= $courseId ?>" class="btn btn-primary btn-enroll btn-lg">Login to Enroll</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="enrollment-price">$<?= number_format($course['price'], 2) ?></div>
                        <ul class="enrollment-features">
                            <li>Full course access</li>
                            <li>Lifetime access</li>
                            <li>Certificate on completion</li>
                            <li>Access to community forum</li>
                            <li>30-day money-back guarantee</li>
                        </ul>
                        <?php if (isLoggedIn()): ?>
                            <a href="/payments/checkout.php?course_id=<?= $courseId ?>" class="btn btn-primary btn-enroll btn-lg">Buy Now</a>
                        <?php else: ?>
                            <a href="/auth/login.php?redirect=/course/details.php?id=<?= $courseId ?>" class="btn btn-primary btn-enroll btn-lg">Login to Buy</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Course Content / Curriculum -->
    <section class="course-content-section">
        <div class="container">
            <h2 class="section-title">Course Curriculum</h2>
            
            <div class="curriculum-container">
                <?php if (empty($modules)): ?>
                    <div style="text-align: center; padding: 3rem; background: var(--gray-50); border-radius: var(--radius-lg);">
                        <p style="color: var(--gray-500);">Course content coming soon...</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($modules as $index => $module): ?>
                        <?php
                        $moduleLessons = Module::getLessons($module['id']);
                        $totalModuleLessons = count($moduleLessons);
                        $completedLessons = 0;
                        if ($isEnrolled && isLoggedIn()) {
                            foreach ($moduleLessons as $lesson) {
                                $lessonProgress = Progress::getLessonProgress($_SESSION['user_id'], $lesson['id']);
                                if ($lessonProgress && $lessonProgress['is_completed']) {
                                    $completedLessons++;
                                }
                            }
                        }
                        ?>
                        <div class="module-card">
                            <div class="module-header" onclick="toggleModule(<?= $index ?>)">
                                <div class="module-info">
                                    <div class="module-icon">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div>
                                        <div class="module-title"><?= sanitize($module['title']) ?></div>
                                        <div class="module-meta"><?= $totalModuleLessons ?> lessons <?= $isEnrolled ? "• $completedLessons/$totalModuleLessons completed" : '' ?></div>
                                    </div>
                                </div>
                                <div class="module-toggle" id="module-toggle-<?= $index ?>">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <div class="lessons-list" id="module-lessons-<?= $index ?>">
                                <?php foreach ($moduleLessons as $lesson): ?>
                                    <?php
                                    $isCompleted = false;
                                    if ($isEnrolled && isLoggedIn()) {
                                        $lessonProgress = Progress::getLessonProgress($_SESSION['user_id'], $lesson['id']);
                                        $isCompleted = $lessonProgress && $lessonProgress['is_completed'];
                                    }
                                    $canAccess = $isEnrolled || $lesson['is_free'];
                                    ?>
                                    <div class="lesson-item <?= $isCompleted ? 'completed' : '' ?>">
                                        <div class="lesson-icon">
                                            <?php if ($isCompleted): ?>
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <?php elseif ($lesson['is_free']): ?>
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <?php else: ?>
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="lesson-info">
                                            <div class="lesson-title"><?= sanitize($lesson['title']) ?></div>
                                            <div class="lesson-meta">
                                                <?php if ($lesson['video_duration']): ?>
                                                    <span><?= floor($lesson['video_duration'] / 60) ?> min</span>
                                                <?php endif; ?>
                                                <?php if ($lesson['is_free']): ?>
                                                    <span style="color: var(--success);">Free preview</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($canAccess): ?>
                                            <a href="/course/lesson.php?course_id=<?= $courseId ?>&lesson_id=<?= $lesson['id'] ?>" class="lesson-link">Start</a>
                                        <?php else: ?>
                                            <span class="lesson-link locked">🔒 Enroll to access</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        function toggleModule(index) {
            const lessonsList = document.getElementById('module-lessons-' + index);
            const toggleIcon = document.getElementById('module-toggle-' + index);
            
            lessonsList.classList.toggle('expanded');
            toggleIcon.classList.toggle('rotated');
        }
        
        // Expand first module by default
        document.addEventListener('DOMContentLoaded', function() {
            toggleModule(0);
        });
    </script>
</body>
</html>
