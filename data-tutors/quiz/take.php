<?php
/**
 * Data Tutors - Quiz Taking Page
 * Interactive quiz with multiple choice questions
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check
if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/auth/login.php');
}

// Get parameters
$quizId = intval($_GET['quiz_id'] ?? 0);
$lessonId = intval($_GET['lesson_id'] ?? 0);

// Fetch quiz
if (!$quizId) {
    redirect(APP_URL . '/dashboard/index.php');
}

$quiz = Quiz::getById($quizId);
if (!$quiz || !$quiz['published']) {
    redirect(APP_URL . '/dashboard/index.php');
}

// Get quiz questions
$questions = Quiz::getQuestions($quizId);
if (empty($questions)) {
    redirect(APP_URL . '/dashboard/index.php');
}

// Get options for each question
foreach ($questions as &$question) {
    $question['options'] = Quiz::getOptions($question['id']);
}

// Shuffle questions if enabled
if ($quiz['shuffle_questions']) {
    shuffle($questions);
}

// Shuffle options
foreach ($questions as &$question) {
    shuffle($question['options']);
}

// Check previous attempts
$previousAttempts = Quiz::getResults($_SESSION['user_id'], $quizId);
$attemptCount = count($previousAttempts);

// Check if user can attempt again
$canAttempt = ($quiz['max_attempts'] == 0) || ($attemptCount < $quiz['max_attempts']);

if (!$canAttempt) {
    $_SESSION['error'] = 'You have reached the maximum number of attempts for this quiz.';
    if ($lessonId > 0) {
        redirect(APP_URL . '/course/lesson.php?course_id=' . $quiz['course_id'] . '&lesson_id=' . $lessonId);
    } else {
        redirect(APP_URL . '/dashboard/index.php');
    }
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_quiz') {
    $answers = $_POST['answers'] ?? [];
    $startTime = $_POST['start_time'] ?? time();
    $timeTaken = time() - intval($startTime);
    
    // Calculate score
    $score = 0;
    $totalPoints = 0;
    $results = [];
    
    foreach ($questions as $question) {
        $totalPoints += floatval($question['points']);
        $questionId = $question['id'];
        $selectedOption = $answers[$questionId] ?? 0;
        
        // Find selected option
        $selectedOpt = null;
        foreach ($question['options'] as $option) {
            if ($option['id'] == $selectedOption) {
                $selectedOpt = $option;
                break;
            }
        }
        
        $isCorrect = ($selectedOpt && $selectedOpt['is_correct']);
        if ($isCorrect) {
            $score += floatval($question['points']);
        }
        
        $results[$questionId] = [
            'selected' => $selectedOption,
            'correct' => $isCorrect,
            'explanation' => $question['explanation']
        ];
    }
    
    $percentage = ($totalPoints > 0) ? ($score / $totalPoints) * 100 : 0;
    $passed = ($percentage >= floatval($quiz['passing_score']));
    
    // Save result
    Quiz::saveResult(
        $_SESSION['user_id'],
        $quizId,
        $score,
        $totalPoints,
        $percentage,
        $passed ? 1 : 0,
        $timeTaken,
        $attemptCount + 1,
        $results
    );
    
    // Store results in session for display
    $_SESSION['quiz_results'] = [
        'quiz_title' => $quiz['title'],
        'score' => $score,
        'total_points' => $totalPoints,
        'percentage' => $percentage,
        'passed' => $passed,
        'time_taken' => $timeTaken,
        'passing_score' => $quiz['passing_score'],
        'questions' => $questions,
        'answers' => $results,
        'show_answers' => $quiz['show_correct_answers']
    ];
    
    // Redirect to results page
    redirect(APP_URL . '/quiz/results.php?quiz_id=' . $quizId . ($lessonId > 0 ? '&lesson_id=' . $lessonId : ''));
}

define('PAGE_TITLE', $quiz['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .quiz-page {
            min-height: 100vh;
            background: var(--gray-50);
            padding-top: 90px;
        }
        .quiz-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem 0;
            position: sticky;
            top: 70px;
            z-index: 10;
        }
        .quiz-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        .quiz-title {
            font-size: 1.25rem;
            color: var(--gray-900);
        }
        .quiz-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        .quiz-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .timer {
            background: var(--gray-100);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-weight: 600;
            color: var(--gray-800);
        }
        .timer.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        .timer.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        .quiz-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }
        .question-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }
        .question-number {
            font-size: 0.875rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .question-text {
            font-size: 1.125rem;
            font-weight: 500;
            color: var(--gray-900);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .option-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
        }
        .option-item:hover {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.02);
        }
        .option-item.selected {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        .option-item input {
            display: none;
        }
        .option-radio {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-300);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: var(--transition);
        }
        .option-item.selected .option-radio {
            border-color: var(--primary);
            background: var(--primary);
        }
        .option-item.selected .option-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }
        .option-text {
            flex: 1;
            color: var(--gray-700);
        }
        .quiz-submit {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1rem 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        }
        .quiz-progress {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        .quiz-progress-bar {
            width: 200px;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
        }
        .quiz-progress-fill {
            height: 100%;
            background: var(--primary);
            transition: width 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .quiz-header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            .quiz-submit {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="quiz-page">
        <div class="quiz-header">
            <div class="quiz-header-content">
                <h1 class="quiz-title"><?= sanitize($quiz['title']) ?></h1>
                <div class="quiz-meta">
                    <?php if ($quiz['time_limit']): ?>
                        <div class="timer" id="quiz-timer">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="timer-display"><?= $quiz['time_limit'] ?>:00</span>
                        </div>
                    <?php endif; ?>
                    <span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?= count($questions) ?> questions
                    </span>
                    <span>Attempt <?= $attemptCount + 1 ?> of <?= $quiz['max_attempts'] ?: 'unlimited' ?></span>
                </div>
            </div>
        </div>
        
        <form method="POST" id="quiz-form">
            <input type="hidden" name="action" value="submit_quiz">
            <input type="hidden" name="start_time" id="start-time" value="<?= time() ?>">
            
            <div class="quiz-content">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-card" id="question-<?= $index + 1 ?>">
                        <div class="question-number">Question <?= $index + 1 ?> of <?= count($questions) ?></div>
                        <div class="question-text"><?= sanitize($question['question_text']) ?></div>
                        
                        <div class="options-list">
                            <?php foreach ($question['options'] as $option): ?>
                                <label class="option-item" onclick="selectOption(<?= $index ?>, <?= $option['id'] ?>)">
                                    <input type="radio" name="answers[<?= $question['id'] ?>]" 
                                           value="<?= $option['id'] ?>" 
                                           id="option-<?= $option['id'] ?>">
                                    <div class="option-radio"></div>
                                    <span class="option-text"><?= sanitize($option['option_text']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="quiz-submit">
                <div class="quiz-progress">
                    <span>Progress:</span>
                    <div class="quiz-progress-bar">
                        <div class="quiz-progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                    <span id="progress-text">0/<?= count($questions) ?> answered</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirmSubmit()">
                    Submit Quiz
                </button>
            </div>
        </form>
    </div>
    
    <script>
        let answeredCount = 0;
        const totalQuestions = <?= count($questions) ?>;
        const timerMinutes = <?= $quiz['time_limit'] ?: 0 ?>;
        let timerInterval;
        
        function selectOption(questionIndex, optionId) {
            const card = document.getElementById('question-' + (questionIndex + 1));
            const options = card.querySelectorAll('.option-item');
            
            options.forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('input').checked = false;
            });
            
            const selected = card.querySelector('#option-' + optionId);
            selected.checked = true;
            selected.closest('.option-item').classList.add('selected');
            
            updateProgress();
        }
        
        function updateProgress() {
            const answered = document.querySelectorAll('input[type="radio"]:checked').length;
            answeredCount = answered;
            const percentage = (answered / totalQuestions) * 100;
            
            document.getElementById('progress-fill').style.width = percentage + '%';
            document.getElementById('progress-text').textContent = answered + '/' + totalQuestions + ' answered';
        }
        
        function confirmSubmit() {
            const unanswered = totalQuestions - answeredCount;
            if (unanswered > 0) {
                return confirm('You have ' + unanswered + ' unanswered question(s). Are you sure you want to submit?');
            }
            return true;
        }
        
        // Timer functionality
        if (timerMinutes > 0) {
            let remaining = timerMinutes * 60;
            const timerDisplay = document.getElementById('timer-display');
            const timerContainer = document.getElementById('quiz-timer');
            
            timerInterval = setInterval(() => {
                remaining--;
                
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                timerDisplay.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                
                if (remaining <= 60) {
                    timerContainer.classList.add('danger');
                } else if (remaining <= 300) {
                    timerContainer.classList.add('warning');
                }
                
                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('quiz-form').submit();
                }
            }, 1000);
        }
        
        // Prevent accidental leave
        window.onbeforeunload = function() {
            if (answeredCount < totalQuestions) {
                return 'You have unanswered questions. Are you sure you want to leave?';
            }
        };
        
        // Remove warning when form is submitted
        document.getElementById('quiz-form').addEventListener('submit', function() {
            window.onbeforeunload = null;
        });
    </script>
</body>
</html>
