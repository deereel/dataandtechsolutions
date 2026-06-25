<?php
/**
 * Data Tutors - Lesson Page
 * Individual lesson page with video, content, and progress tracking
 */

require_once '../config/config.php';
require_once '../config/database.php';

 // Authentication check - users must be logged in to access lessons
 if (!isLoggedIn()) {
     $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
     redirect(APP_URL . '/auth/login.php');
 }

// Get parameters
$courseId = intval($_GET['course_id'] ?? 0);
$lessonId = intval($_GET['lesson_id'] ?? 0);

// Validate and fetch course
if (!$courseId) {
    redirect(APP_URL . '/course/index.php');
}

$course = Course::getById($courseId);
if (!$course) {
    redirect(APP_URL . '/course/index.php');
}

 // Check enrollment
 $isEnrolled = Enrollment::isEnrolled($_SESSION['user_id'], $courseId);

 // If not enrolled and lesson is not free, redirect to course details
 if (!$isEnrolled) {
     $lesson = Lesson::getById($lessonId);
     if (!$lesson || !$lesson['is_free']) {
         redirect(APP_URL . '/course/details.php?id=' . $courseId);
     }
 }

// Fetch lesson
if ($lessonId > 0) {
    $lesson = Lesson::getById($lessonId);
} else {
    // Get first lesson
    $modules = Course::getModules($courseId);
    if (!empty($modules)) {
        $lessons = Module::getLessons($modules[0]['id']);
        if (!empty($lessons)) {
            $lesson = $lessons[0];
            $lessonId = $lesson['id'];
        }
    }
}

if (!$lesson) {
    redirect(APP_URL . '/course/details.php?id=' . $courseId);
}

// Get previous and next lessons
$prevLesson = Lesson::getPrevious($lessonId, $lesson['module_id']);
$nextLesson = Lesson::getNext($lessonId, $lesson['module_id']);

// If no next lesson in module, try next module
if (!$nextLesson) {
    $modules = Course::getModules($courseId);
    $currentModuleIndex = 0;
    foreach ($modules as $index => $m) {
        if ($m['id'] == $lesson['module_id']) {
            $currentModuleIndex = $index;
            break;
        }
    }
    
    // Find next module
    for ($i = $currentModuleIndex + 1; $i < count($modules); $i++) {
        $nextModuleLessons = Module::getLessons($modules[$i]['id']);
        if (!empty($nextModuleLessons)) {
            $nextLesson = $nextModuleLessons[0];
            break;
        }
    }
}

// Get lesson resources
$resources = Lesson::getResources($lessonId);

// Get lesson progress
$lessonProgress = Progress::getLessonProgress($_SESSION['user_id'], $lessonId);
$isCompleted = $lessonProgress && $lessonProgress['is_completed'];

// Get course progress
$courseProgress = DatabaseConnection::fetch(
    "SELECT * FROM user_course_progress WHERE user_id = ? AND course_id = ?",
    [$_SESSION['user_id'], $courseId]
);

// Get all modules and lessons for sidebar navigation
$allModules = Course::getModules($courseId);

// Mark lesson as accessed
Progress::recalculateCourseProgress($_SESSION['user_id'], $courseId);

        // Handle mark as complete
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'complete') {
            Progress::markLessonComplete($_SESSION['user_id'], $lessonId);
            
            // Update last accessed
            DatabaseConnection::query(
                "UPDATE user_course_progress SET last_lesson_id = ?, last_accessed_at = NOW() WHERE user_id = ? AND course_id = ?",
                [$lessonId, $_SESSION['user_id'], $courseId]
            );
            
            $isCompleted = true;
            $_SESSION['success'] = 'Lesson marked as complete!';
            
            // Redirect to next lesson if available
            if ($nextLesson) {
                redirect(APP_URL . '/course/lesson.php?course_id=' . $courseId . '&lesson_id=' . $nextLesson['id']);
            } else {
                // If no next lesson, redirect to course details page
                redirect(APP_URL . '/course/details.php?id=' . $courseId);
            }
        }

define('PAGE_TITLE', $lesson['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .lesson-page {
            display: grid;
            grid-template-columns: 1fr 350px;
            min-height: 100vh;
            padding-top: 70px;
        }
        .lesson-main {
            background: var(--gray-50);
        }
        .lesson-video {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
        }
        .lesson-video iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .lesson-content-area {
            padding: 2rem;
            max-width: 900px;
        }
        .lesson-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .lesson-nav {
            display: flex;
            gap: 0.5rem;
        }
        .lesson-nav a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            color: var(--gray-700);
            font-size: 0.875rem;
            transition: var(--transition);
        }
        .lesson-nav a:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }
        .lesson-nav a.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .lesson-title {
            font-size: 1.75rem;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }
        .lesson-description {
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .lesson-body {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .lesson-body h2 {
            font-size: 1.25rem;
            margin: 1.5rem 0 1rem;
        }
        .lesson-body h3 {
            font-size: 1.125rem;
            margin: 1.25rem 0 0.75rem;
        }
        .lesson-body p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        .lesson-body ul, .lesson-body ol {
            margin: 1rem 0 1rem 1.5rem;
        }
        .lesson-body li {
            margin-bottom: 0.5rem;
        }
        
        /* Complete Button */
        .complete-btn-container {
            margin-bottom: 2rem;
        }
        .btn-complete {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: var(--success);
            color: white;
            border-radius: var(--radius);
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        .btn-complete:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        .btn-complete.completed {
            background: var(--gray-100);
            color: var(--success);
        }
        .btn-complete.completed:hover {
            background: var(--gray-200);
        }
        
        /* Resources */
        .resources-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .resources-title {
            font-size: 1.125rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .resource-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            transition: var(--transition);
            color: inherit;
            text-decoration: none;
        }
        .resource-item:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }
        .resource-icon {
            width: 40px;
            height: 40px;
            background: var(--gray-100);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        .resource-info {
            flex: 1;
        }
        .resource-name {
            font-weight: 500;
            color: var(--gray-800);
        }
        .resource-size {
            font-size: 0.8rem;
            color: var(--gray-500);
        }
        .resource-download {
            color: var(--primary);
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        /* Sidebar */
        .lesson-sidebar {
            background: white;
            border-left: 1px solid var(--gray-200);
            height: calc(100vh - 70px);
            position: sticky;
            top: 70px;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }
        .sidebar-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .sidebar-progress {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .progress-bar-container {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        /* Curriculum Sidebar */
        .curriculum-list {
            padding: 1rem 0;
        }
        .curriculum-module {
            border-bottom: 1px solid var(--gray-100);
        }
        .curriculum-module-header {
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .curriculum-lessons {
            padding: 0 0.5rem;
        }
        .curriculum-lesson {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 0.25rem;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
            color: inherit;
            text-decoration: none;
        }
         .curriculum-lesson:hover {
            background: var(--gray-50);
        }
        .curriculum-lesson.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }
        .curriculum-lesson.completed {
            color: var(--success);
        }
        .curriculum-lesson.locked {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }
        .curriculum-lesson.locked .lesson-status-icon {
            border-color: var(--gray-300);
            background: var(--gray-100);
        }
        .lesson-status-icon {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-300);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .curriculum-lesson.completed .lesson-status-icon {
            border-color: var(--success);
            background: var(--success);
            color: white;
        }
        .curriculum-lesson.active .lesson-status-icon {
            border-color: var(--primary);
        }
        .lesson-status-icon svg {
            width: 12px;
            height: 12px;
        }
        .lesson-info {
            flex: 1;
        }
        .lesson-name {
            color: var(--gray-800);
        }
        .curriculum-lesson.active .lesson-name {
            color: var(--primary);
        }
        .lesson-duration {
            font-size: 0.75rem;
            color: var(--gray-400);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .lesson-page {
                grid-template-columns: 1fr;
            }
            .lesson-sidebar {
                position: fixed;
                left: -350px;
                width: 350px;
                height: calc(100vh - 70px);
                z-index: 100;
                transition: left 0.3s ease;
            }
            .lesson-sidebar.open {
                left: 0;
            }
            .sidebar-toggle {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                background: var(--primary);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: var(--shadow-lg);
                z-index: 101;
                cursor: pointer;
                border: none;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="lesson-page">
        <div class="lesson-main">
            <!-- Video Section -->
            <?php if ($lesson['video_url']): ?>
                <div class="lesson-video">
                    <?php
                    // Handle YouTube URLs and embed codes
                    $videoUrl = $lesson['video_url'];
                    $embedUrl = '';
                    
                    // Check if it's already an embed code
                    if (strpos($videoUrl, '<iframe') !== false) {
                        // Extract src from iframe
                        preg_match('/src="([^"]+)"/', $videoUrl, $matches);
                        if (!empty($matches[1])) {
                            $embedUrl = $matches[1];
                        }
                    } else {
                        // Convert YouTube watch URL to embed URL
                        $originalUrl = $videoUrl;
                        $videoUrl = strtolower($videoUrl); // Make it case insensitive for searching
                        
                        if (strpos($videoUrl, 'youoube.com') !== false) {
                            $originalUrl = str_replace('youoube.com', 'youtube.com', $originalUrl);
                        }
                        
                        if (strpos($originalUrl, 'youtube.com/watch?v=') !== false) {
                            preg_match('/v=([a-zA-Z0-9_-]+)/', $originalUrl, $matches);
                            if (!empty($matches[1])) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                // Preserve any additional parameters (like start, end, etc.)
                                if (strpos($originalUrl, '&') !== false) {
                                    $params = substr($originalUrl, strpos($originalUrl, '&'));
                                    $embedUrl .= $params;
                                }
                            }
                        } elseif (strpos($originalUrl, 'youtu.be/') !== false) {
                            // Handle youtu.be short URLs
                            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $originalUrl, $matches);
                            if (!empty($matches[1])) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                if (strpos($originalUrl, '?') !== false) {
                                    $params = substr($originalUrl, strpos($originalUrl, '?'));
                                    $embedUrl .= $params;
                                }
                            }
                        } elseif (strpos($originalUrl, 'youtube.com/embed/') !== false) {
                            // Already an embed URL
                            $embedUrl = $originalUrl;
                        } else {
                            // Treat as direct video URL
                            $embedUrl = $originalUrl;
                        }
                    }
                    
                    // Add start and stop parameters to YouTube embed URL
                    if (strpos($embedUrl, 'youtube.com/embed/') !== false) {
                        $hasParams = strpos($embedUrl, '?') !== false;
                        
                        if ($lesson['video_start'] > 0) {
                            $embedUrl .= ($hasParams ? '&' : '?') . 'start=' . $lesson['video_start'];
                            $hasParams = true;
                        }
                        
                        if ($lesson['video_stop'] > 0 && $lesson['video_stop'] > $lesson['video_start']) {
                            $embedUrl .= ($hasParams ? '&' : '?') . 'end=' . $lesson['video_stop'];
                        }
                    }
                    ?>
                    <div id="youtube-player"></div>
                    
                    <script src="https://www.youtube.com/iframe_api"></script>
                    <script>
                        let player;
                        
                        function onYouTubeIframeAPIReady() {
                            // Extract video ID from video URL directly (more reliable)
                            const videoUrl = "<?= sanitize($lesson['video_url']) ?>";
                            let videoId = null;
                            
                            // Try to extract from various YouTube URL formats
                            const idPatterns = [
                                /(?:youtube\.com\/watch\?.*v=)([a-zA-Z0-9_-]+)/,
                                /(?:youtu\.be\/)([a-zA-Z0-9_-]+)/,
                                /(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/
                            ];
                            
                            for (let pattern of idPatterns) {
                                const match = videoUrl.match(pattern);
                                if (match) {
                                    videoId = match[1];
                                    break;
                                }
                            }
                            
                            if (videoId) {
                                player = new YT.Player('youtube-player', {
                                    height: '100%',
                                    width: '100%',
                                    videoId: videoId,
                                    playerVars: {
                                        'start': <?= $lesson['video_start'] ?>,
                                        'rel': 0
                                    },
                                    events: {
                                        'onReady': onPlayerReady,
                                        'onStateChange': onPlayerStateChange
                                    }
                                });
                            }
                        }
                        
                        function onPlayerReady(event) {
                            // Start playing when ready
                            event.target.playVideo();
                        }
                        
                        function onPlayerStateChange(event) {
                            // Check if we need to stop the video
                            const stopTime = <?= $lesson['video_stop'] ?>;
                            if (stopTime > 0) {
                                if (event.data === YT.PlayerState.PLAYING) {
                                    checkTime();
                                }
                            }
                        }
                        
                        function checkTime() {
                            const stopTime = <?= $lesson['video_stop'] ?>;
                            if (player && stopTime > 0) {
                                const currentTime = player.getCurrentTime();
                                if (currentTime >= stopTime) {
                                    player.pauseVideo();
                                } else {
                                    setTimeout(checkTime, 500); // Check every 500ms
                                }
                            }
                        }
                    </script>
                </div>
            <?php endif; ?>
            
            <div class="lesson-content-area">
                <!-- Lesson Navigation -->
                <div class="lesson-header">
                    <a href="/course/details.php?id=<?= $courseId ?>" class="btn btn-secondary btn-sm">
                        ← Back to Course
                    </a>
                    <div class="lesson-nav">
                        <?php if ($prevLesson): ?>
                            <a href="?course_id=<?= $courseId ?>&lesson_id=<?= $prevLesson['id'] ?>">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Previous
                            </a>
                        <?php else: ?>
                            <span class="disabled">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Previous
                            </span>
                        <?php endif; ?>
                        
                        <?php 
                        $quiz = Quiz::getByLesson($lessonId);
                        $canProceed = true;
                        
                        if ($quiz) {
                            $canProceed = Quiz::hasPassed($_SESSION['user_id'], $quiz['id']);
                        }
                        
                        if ($nextLesson && $canProceed): ?>
                            <a href="?course_id=<?= $courseId ?>&lesson_id=<?= $nextLesson['id'] ?>">
                                Next
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        <?php elseif ($nextLesson && !$canProceed): ?>
                            <span class="disabled" title="You must pass the quiz with 70% or more to proceed">
                                Next
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        <?php else: ?>
                            <span class="disabled">
                                Next
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Lesson Title -->
                <h1 class="lesson-title"><?= sanitize($lesson['title']) ?></h1>
                
                <!-- Complete Button -->
                <div class="complete-btn-container">
                    <form method="POST">
                        <input type="hidden" name="action" value="complete">
                        <button type="submit" class="btn-complete <?= $isCompleted ? 'completed' : '' ?>">
                            <?php if ($isCompleted): ?>
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Completed
                            <?php else: ?>
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Mark as Complete
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
                
                <!-- Lesson Content -->
                <?php if ($lesson['content']): ?>
                    <div class="lesson-body">
                        <?= $lesson['content'] ?>
                    </div>
                <?php endif; ?>
                
                <!-- Resources -->
                <?php if (!empty($resources)): ?>
                    <div class="resources-section">
                        <h3 class="resources-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Downloadable Resources
                        </h3>
                        <?php foreach ($resources as $resource): ?>
                            <a href="<?= sanitize($resource['file_path']) ?>" class="resource-item" download>
                                <div class="resource-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="resource-info">
                                    <div class="resource-name"><?= sanitize($resource['title']) ?></div>
                                    <?php if ($resource['file_size']): ?>
                                        <div class="resource-size"><?= formatBytes($resource['file_size']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <span class="resource-download">Download</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Quiz Link (if available) -->
                <?php
                $quiz = Quiz::getByLesson($lessonId);
                if ($quiz):
                ?>
                    <div class="resources-section" style="background: rgba(37, 99, 235, 0.05); border: 1px solid rgba(37, 99, 235, 0.2);">
                        <h3 class="resources-title">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Lesson Quiz
                        </h3>
                        <p style="color: var(--gray-600); margin-bottom: 1rem;">Test your knowledge with a quick quiz!</p>
                        <a href="/quiz/take.php?quiz_id=<?= $quiz['id'] ?>&lesson_id=<?= $lessonId ?>" class="btn btn-primary">
                            Take Quiz
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Sidebar - Course Curriculum -->
        <div class="lesson-sidebar" id="lesson-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title"><?= sanitize($course['title']) ?></div>
                <?php if ($courseProgress): ?>
                    <div class="sidebar-progress">
                        <?= number_format($courseProgress['progress_percentage'], 0) ?>% complete
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: <?= $courseProgress['progress_percentage'] ?>%"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="curriculum-list">
                <?php 
                // Flatten all lessons to check for quiz passing requirements
                $allLessons = [];
                foreach ($allModules as $module) {
                    $moduleLessons = Module::getLessons($module['id']);
                    foreach ($moduleLessons as $mLesson) {
                        $allLessons[] = $mLesson;
                    }
                }
                
                // Sort lessons by order (assuming order_index is sequential)
                usort($allLessons, function($a, $b) {
                    $moduleOrderA = Module::getById($a['module_id'])['order_index'];
                    $moduleOrderB = Module::getById($b['module_id'])['order_index'];
                    
                    if ($moduleOrderA == $moduleOrderB) {
                        return $a['order_index'] - $b['order_index'];
                    }
                    return $moduleOrderA - $moduleOrderB;
                });
                
                // Find the index of the first unpassed quiz lesson
                $lockedIndex = null;
                foreach ($allLessons as $index => $mLesson) {
                    $quiz = Quiz::getByLesson($mLesson['id']);
                    if ($quiz && !Quiz::hasPassed($_SESSION['user_id'], $quiz['id'])) {
                        $lockedIndex = $index + 1; // Lock lessons after this one
                        break;
                    }
                }
                ?>
                
                <?php foreach ($allModules as $module): ?>
                    <?php $moduleLessons = Module::getLessons($module['id']); ?>
                    <div class="curriculum-module">
                        <div class="curriculum-module-header">
                            <?= sanitize($module['title']) ?>
                            <span style="font-weight: 400; font-size: 0.75rem; color: var(--gray-400);">
                                <?= count($moduleLessons) ?> lessons
                            </span>
                        </div>
                        <div class="curriculum-lessons">
                            <?php foreach ($moduleLessons as $mLesson): ?>
                                <?php
                                $mLessonCompleted = false;
                                $mLessonProgress = Progress::getLessonProgress($_SESSION['user_id'], $mLesson['id']);
                                if ($mLessonProgress && $mLessonProgress['is_completed']) {
                                    $mLessonCompleted = true;
                                }
                                
                                // Check if this lesson is locked
                                $isLocked = false;
                                $lessonIndex = array_search($mLesson['id'], array_column($allLessons, 'id'));
                                if ($lockedIndex !== null && $lessonIndex >= $lockedIndex) {
                                    $isLocked = true;
                                }
                                ?>
                                <a href="?course_id=<?= $courseId ?>&lesson_id=<?= $mLesson['id'] ?>" 
                                   class="curriculum-lesson <?= $mLesson['id'] == $lessonId ? 'active' : '' ?> <?= $mLessonCompleted ? 'completed' : '' ?> <?= $isLocked ? 'locked' : '' ?>"
                                   <?= $isLocked ? 'title="You must pass the previous quiz to unlock this lesson"' : '' ?>>
                                    <div class="lesson-status-icon">
                                        <?php if ($mLessonCompleted): ?>
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        <?php elseif ($isLocked): ?>
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="lesson-info">
                                        <div class="lesson-name"><?= sanitize($mLesson['title']) ?></div>
                                        <?php if ($mLesson['video_duration']): ?>
                                            <div class="lesson-duration"><?= floor($mLesson['video_duration'] / 60) ?> min</div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Mobile Sidebar Toggle -->
        <button class="sidebar-toggle" id="sidebar-toggle" onclick="toggleSidebar()">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    
    <script>
        function toggleSidebar() {
            document.getElementById('lesson-sidebar').classList.toggle('open');
        }
    </script>
</body>
</html>

<?php
// Helper function for file size formatting
function formatBytes($bytes, $precision = 2) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes, $k));
    return round($bytes / pow($k, $i), $precision) . ' ' . $sizes[$i];
}
?>
