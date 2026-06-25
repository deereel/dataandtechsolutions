<?php
/**
 * Data Tutors - Forum Ask Question Page
 * Page for users to ask new questions in the forum
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect(APP_URL . '/auth/login.php');
}

// Page title
define('PAGE_TITLE', 'Ask Question');

// Handle question submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $categoryId = $_POST['category'] ?? '';

    if (empty($title)) {
        $error = 'Question title is required';
    } elseif (empty($content)) {
        $error = 'Question content is required';
    } elseif (empty($categoryId)) {
        $error = 'Please select a category';
    } else {
        // Create question in database
        $userId = getCurrentUser()['id'];
        
        $questionId = Forum::createQuestion($userId, [
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($questionId) {
            $success = 'Your question has been posted successfully!';
            
            // Redirect to the question page after a short delay
            echo '<script>setTimeout(function() { window.location.href = "' . APP_URL . '/forum/question.php?id=' . $questionId . '"; }, 2000);</script>';
        } else {
            $error = 'Failed to post question. Please try again.';
        }
    }
}

// Get categories for dropdown
$categories = Forum::getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <meta name="description" content="Ask questions and get help from the Data Tutors community. Join the discussion about Excel, data analysis, SQL, Python, Power BI, and more.">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <style>
        .ask-hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }

        .ask-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .ask-hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .ask-content {
            padding: 6rem 0;
            background: white;
        }

        .content-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .ask-form {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.9375rem;
            transition: var(--transition);
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            min-height: 200px;
            resize: vertical;
        }

        .submit-button {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .submit-button:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
        }

        .success-message {
            background: var(--success);
            color: white;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .error-message {
            background: var(--danger);
            color: white;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .tips-section {
            background: var(--gray-50);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-top: 2rem;
        }

        .tips-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--gray-900);
        }

        .tips-section ul {
            list-style: none;
            padding: 0;
        }

        .tips-section li {
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 0.75rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .tips-section li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--success);
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .ask-hero {
                padding: 4rem 0;
            }

            .ask-hero h1 {
                font-size: 2rem;
            }

            .ask-hero p {
                font-size: 1rem;
            }

            .ask-content {
                padding: 4rem 0;
            }

            .ask-form {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="ask-hero">
        <div class="container">
            <h1>Ask a Question</h1>
            <p>Get help from the Data Tutors community. Our experts and fellow learners are here to assist you.</p>
        </div>
    </section>

    <!-- Ask Content -->
    <section class="ask-content">
        <div class="content-container">
            <div class="ask-form">
                <?php if ($success): ?>
                    <div class="success-message show"><?= $success ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="error-message show"><?= $error ?></div>
                <?php endif; ?>

                <form id="ask-form" method="POST">
                    <div class="form-group">
                        <label for="title">Question Title *</label>
                        <input type="text" id="title" name="title" class="form-input" 
                               placeholder="Ask your question in a clear and concise manner" 
                               required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>">
                                    <?= $category['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="content">Question Content *</label>
                        <textarea id="content" name="content" class="form-textarea" 
                                  placeholder="Describe your question in detail. Include any relevant context, code, or screenshots."
                                  required></textarea>
                    </div>

                    <button type="submit" id="submit-button" class="submit-button">
                        Post Question
                    </button>
                </form>

                <div class="tips-section">
                    <h3>Tips for asking better questions:</h3>
                    <ul>
                        <li>Be specific about your problem</li>
                        <li>Include relevant details and context</li>
                        <li>Use clear and concise language</li>
                        <li>Check if similar questions have been asked before</li>
                        <li>Format your question for readability</li>
                        <li>Include code examples or error messages (if applicable)</li>
                        <li>Be respectful and polite</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        // Form submission handler
        document.getElementById('ask-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Posting...';
            
            this.submit();
        });

        // Real-time validation
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const categorySelect = document.getElementById('category');
        const submitButton = document.getElementById('submit-button');

        function validateForm() {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            const category = categorySelect.value;

            submitButton.disabled = !title || !content || !category;
        }

        titleInput.addEventListener('input', validateForm);
        contentInput.addEventListener('input', validateForm);
        categorySelect.addEventListener('change', validateForm);

        // Initialize validation
        validateForm();
    </script>
</body>
</html>