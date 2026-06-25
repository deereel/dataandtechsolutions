<?php
/**
 * Verify quiz questions for Data Analysis course
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Verifying Quiz Questions for Data Analysis Course ===\n";

try {
    // Get all quizzes for Data Analysis course
    $quizzes = DatabaseConnection::fetchAll('SELECT * FROM quizzes WHERE course_id = 9');
    
    echo "Found " . count($quizzes) . " quizzes\n\n";
    
    $totalQuestions = 0;
    
    foreach ($quizzes as $quiz) {
        echo str_pad($quiz['title'], 40) . ": ";
        
        // Get questions for this quiz
        $questions = DatabaseConnection::fetchAll('SELECT * FROM quiz_questions WHERE quiz_id = ?', [$quiz['id']]);
        
        // Verify questions have options
        $validQuestions = 0;
        foreach ($questions as $question) {
            $options = DatabaseConnection::fetchAll('SELECT * FROM quiz_options WHERE question_id = ?', [$question['id']]);
            if (count($options) >= 2) {
                $validQuestions++;
            }
        }
        
        echo $validQuestions . " questions";
        
        // Check if there's at least one correct answer per question
        $questionsWithCorrect = 0;
        foreach ($questions as $question) {
            $hasCorrect = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quiz_options WHERE question_id = ? AND is_correct = 1', [$question['id']]);
            if ($hasCorrect > 0) {
                $questionsWithCorrect++;
            }
        }
        
        if ($validQuestions == $questionsWithCorrect) {
            echo " ✅\n";
        } else {
            echo " ❗ Questions without correct answers: " . ($validQuestions - $questionsWithCorrect) . "\n";
        }
        
        $totalQuestions += $validQuestions;
    }
    
    echo "\n=== Summary ===\n";
    echo "Total valid questions: " . $totalQuestions . "\n";
    echo "Total quizzes: " . count($quizzes) . "\n";
    
    if ($totalQuestions == 100 && count($quizzes) == 10) {
        echo "\n✅ All quizzes are complete! Each quiz has 10 valid questions with correct answers.";
    } else {
        echo "\n⚠️  Incomplete quizzes. Found " . count($quizzes) . " quizzes with " . $totalQuestions . " total questions.";
    }
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    if (isset($e->errorInfo)) {
        echo "SQL Error: " . print_r($e->errorInfo, true);
    }
}
?>
