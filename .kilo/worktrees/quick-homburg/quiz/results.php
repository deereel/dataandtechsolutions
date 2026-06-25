<?php
/**
 * Data Tutors - Quiz Results Page
 * Display quiz results and feedback
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check
if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php');
}

// Get parameters
$quizId = intval($_GET['quiz_id'] ?? 0);
$lessonId = intval($_GET['lesson_id'] ?? 0);

// Get results from session
if (!isset($_SESSION['quiz_results'])) {
    redirect(APP_URL . '/dashboard/index.php');
}

$results = $_SESSION['quiz_results'];
unset($_SESSION['quiz_results']);

// Get course ID for back link
$courseId = $lessonId > 0 ? intval(DatabaseConnection::fetchColumn(
    "SELECT course_id FROM modules WHERE id = (SELECT module_id FROM lessons WHERE id = ?)",
    [$lessonId]
)) : 0;

define('PAGE_TITLE', 'Quiz Results');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .results-page {
            min-height: 100vh;
            background: var(--gray-50);
            padding-top: 90px;
        }
        .results-header {
            background: <?= $results['passed'] ? 'linear-gradient(135deg, #059669 0%, #10b981 100%)' : 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)' ?>;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .results-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .results-icon svg {
            width: 40px;
            height: 40px;
        }
        .results-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .results-message {
            font-size: 1.125rem;
            opacity: 0.9;
        }
        .results-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        .results-content {
            max-width: 800px;
            margin: -2rem auto 0;
            padding: 0 1.5rem 4rem;
        }
        .score-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
        }
        .score-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
        }
        .score-item {
            text-align: center;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
        }
        .score-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .score-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }
        .questions-review {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .review-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-100);
            font-size: 1.25rem;
            font-weight: 600;
        }
        .question-review {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .question-review:last-child {
            border-bottom: none;
        }
        .question-review.correct {
            background: rgba(16, 185, 129, 0.03);
        }
        .question-review.incorrect {
            background: rgba(239, 68, 68, 0.03);
        }
        .question-review-header {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .review-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .question-review.correct .review-icon {
            background: var(--success);
            color: white;
        }
        .question-review.incorrect .review-icon {
            background: var(--danger);
            color: white;
        }
        .question-text {
            font-weight: 500;
            color: var(--gray-800);
        }
        .answer-comparison {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
        }
        .answer-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-200);
        }
        .answer-row:last-child {
            border-bottom: none;
        }
        .answer-label {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .answer-value {
            font-weight: 500;
            font-size: 0.875rem;
        }
        .your-answer {
            color: var(--danger);
        }
        .your-answer.correct {
            color: var(--success);
        }
        .correct-answer {
            color: var(--success);
        }
        .explanation {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(37, 99, 235, 0.05);
            border-radius: var(--radius);
            border-left: 3px solid var(--primary);
        }
        .explanation-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .explanation-text {
            color: var(--gray-700);
            font-size: 0.875rem;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .results-stats {
                gap: 1.5rem;
            }
            .score-details {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="results-page">
        <!-- Results Header -->
        <div class="results-header">
            <div class="results-icon">
                <?php if ($results['passed']): ?>
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?php else: ?>
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <?php endif; ?>
            </div>
            <h1 class="results-title"><?= $results['passed'] ? 'Congratulations!' : 'Keep Practicing!' ?></h1>
            <p class="results-message">
                <?= $results['passed'] 
                    ? 'You passed the quiz!' 
                    : 'You didn\'t pass this time, but don\'t give up!' ?>
            </p>
            <div class="results-stats">
                <div class="stat-item">
                    <div class="stat-value"><?= number_format($results['percentage'], 0) ?>%</div>
                    <div class="stat-label">Score</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= number_format($results['passing_score'], 0) ?>%</div>
                    <div class="stat-label">Passing Score</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= formatTime($results['time_taken']) ?></div>
                    <div class="stat-label">Time Taken</div>
                </div>
            </div>
        </div>
        
        <div class="results-content">
            <!-- Score Card -->
            <div class="score-card">
                <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Your Results</h2>
                <div class="score-details">
                    <div class="score-item">
                        <div class="score-value"><?= number_format($results['score'], 1) ?></div>
                        <div class="score-label">Points Earned</div>
                    </div>
                    <div class="score-item">
                        <div class="score-value"><?= number_format($results['total_points'], 1) ?></div>
                        <div class="score-label">Total Points</div>
                    </div>
                    <div class="score-item">
                        <div class="score-value">
                            <?php
                            $correctCount = 0;
                            foreach ($results['answers'] as $answer) {
                                if ($answer['correct']) $correctCount++;
                            }
                            echo $correctCount;
                            ?>
                        </div>
                        <div class="score-label">Correct Answers</div>
                    </div>
                    <div class="score-item">
                        <div class="score-value">
                            <?= count($results['questions']) - $correctCount ?>
                        </div>
                        <div class="score-label">Incorrect</div>
                    </div>
                </div>
            </div>
            
            <!-- Questions Review -->
            <?php if ($results['show_answers']): ?>
                <div class="questions-review">
                    <div class="review-header">Review Your Answers</div>
                    <?php foreach ($results['questions'] as $index => $question): ?>
                        <?php
                        $answer = $results['answers'][$question['id']];
                        $isCorrect = $answer['correct'];
                        $selectedOption = null;
                        $correctOption = null;
                        
                        foreach ($question['options'] as $option) {
                            if ($option['id'] == $answer['selected']) {
                                $selectedOption = $option['option_text'];
                            }
                            if ($option['is_correct']) {
                                $correctOption = $option['option_text'];
                            }
                        }
                        ?>
                        <div class="question-review <?= $isCorrect ? 'correct' : 'incorrect' ?>">
                            <div class="question-review-header">
                                <div class="review-icon">
                                    <?php if ($isCorrect): ?>
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <?php else: ?>
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="question-text">
                                    <?= ($index + 1) . '. ' . sanitize($question['question_text']) ?>
                                </div>
                            </div>
                            
                            <div class="answer-comparison">
                                <div class="answer-row">
                                    <span class="answer-label">Your Answer:</span>
                                    <span class="answer-value <?= $isCorrect ? 'correct' : 'your-answer' ?>">
                                        <?= sanitize($selectedOption ?: 'Not answered') ?>
                                    </span>
                                </div>
                                <?php if (!$isCorrect && $correctOption): ?>
                                    <div class="answer-row">
                                        <span class="answer-label">Correct Answer:</span>
                                        <span class="answer-value correct-answer">
                                            <?= sanitize($correctOption) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($answer['explanation']): ?>
                                <div class="explanation">
                                    <div class="explanation-title">Explanation</div>
                                    <div class="explanation-text"><?= sanitize($answer['explanation']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <?php if ($results['passed'] && $lessonId > 0): ?>
                    <?php
                    // Get next lesson
                    $lesson = Lesson::getById($lessonId);
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
                    ?>
                    <?php if ($nextLesson): ?>
                        <a href="/course/lesson.php?course_id=<?= $courseId ?>&lesson_id=<?= $nextLesson['id'] ?>" class="btn btn-primary btn-lg">
                            Next Lesson
                        </a>
                        <script>
                            // Auto-redirect to next lesson after 3 seconds
                            setTimeout(function() {
                                window.location.href = '/course/lesson.php?course_id=<?= $courseId ?>&lesson_id=<?= $nextLesson['id'] ?>';
                            }, 3000);
                        </script>
                    <?php else: ?>
                        <a href="/course/details.php?id=<?= $courseId ?>" class="btn btn-primary btn-lg">
                            Course Complete!
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($lessonId > 0): ?>
                        <a href="/course/lesson.php?course_id=<?= $courseId ?>&lesson_id=<?= $lessonId ?>" class="btn btn-secondary btn-lg">
                            Back to Lesson
                        </a>
                    <?php endif; ?>
                    <a href="/dashboard/index.php" class="btn btn-primary btn-lg">
                        Go to Dashboard
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
function formatTime($seconds) {
    if ($seconds < 60) {
        return $seconds . 's';
    }
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    return $minutes . 'm ' . $secs . 's';
}
?>
