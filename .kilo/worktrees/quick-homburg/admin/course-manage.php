<?php
/**
 * Data Tutors - Course Management Page
 * Manage course modules, lessons, content, and quizzes
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Handle get resources request separately (GET method) - must come before any HTML output
if (isset($_GET['action']) && $_GET['action'] === 'get_resources') {
    // Authentication check for API endpoint
    if (!isAdminLoggedIn() || !isAdmin()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        http_response_code(401);
        exit;
    }
    
    $lessonId = intval($_GET['lesson_id'] ?? 0);
    error_log('get_resources called for lesson ID: ' . $lessonId);
    
    if ($lessonId > 0) {
        $resources = LessonResource::getByLesson($lessonId);
        error_log('Resources found: ' . print_r($resources, true));
        
        header('Content-Type: application/json');
        echo json_encode(['resources' => $resources]);
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode(['resources' => []]);
    exit;
}

// Authentication check
if (!isAdminLoggedIn() || !isAdmin()) {
    redirect(APP_URL . '/admin/login.php');
}

// Get course ID from URL
$courseId = intval($_GET['course_id'] ?? 0);
if ($courseId <= 0) {
    redirect(APP_URL . '/admin/courses.php');
}

// Get course details
$course = Course::getById($courseId);
if (!$course) {
    redirect(APP_URL . '/admin/courses.php');
}

// Get current admin user
$currentAdmin = getCurrentAdmin();

$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdminUser()) {
    $action = $_POST['action'] ?? '';
    
    // ============ MODULE ACTIONS ============
    if ($action === 'add_module') {
        $title = sanitize($_POST['title'] ?? '');
        $description = $_POST['description'] ?? '';
        
        $orderIndex = Module::getMaxOrderIndex($courseId) + 1;
        $moduleId = Module::create([
            'course_id' => $courseId,
            'title' => $title,
            'description' => $description,
            'order_index' => $orderIndex,
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ]);
        
        if ($moduleId) {
            $success = 'Module created successfully.';
            logActivity($_SESSION['admin_id'], 'create_module', 'modules', $moduleId, null, ['title' => $title, 'course_id' => $courseId]);
        } else {
            $error = 'Failed to create module.';
        }
    }
    
    elseif ($action === 'update_module') {
        $moduleId = intval($_POST['module_id'] ?? 0);
        if ($moduleId > 0) {
            Module::update($moduleId, [
                'title' => sanitize($_POST['title'] ?? ''),
                'description' => $_POST['description'] ?? '',
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ]);
            $success = 'Module updated successfully.';
        }
    }
    
    elseif ($action === 'delete_module') {
        $moduleId = intval($_POST['module_id'] ?? 0);
        if ($moduleId > 0) {
            Module::delete($moduleId);
            $success = 'Module deleted successfully.';
        }
    }
    
     elseif ($action === 'reorder_modules') {
         // Handle module reordering
         $moduleOrders = $_POST['module_order'] ?? [];
         if (!empty($moduleOrders)) {
             foreach ($moduleOrders as $index => $moduleId) {
                 Module::updateOrder($moduleId, $index + 1);
             }
             $success = 'Module order updated successfully.';
         }
     }
     
      elseif ($action === 'reorder_lessons') {
          // Handle lesson reordering
          error_log('reorder_lessons called');
          error_log('POST data: ' . print_r($_POST, true));
          $lessonOrders = $_POST['lesson_order'] ?? [];
          $moduleId = intval($_POST['module_id'] ?? 0);
          error_log('Lesson orders: ' . print_r($lessonOrders, true));
          error_log('Module ID: ' . $moduleId);
          if (!empty($lessonOrders) && $moduleId > 0) {
              foreach ($lessonOrders as $index => $lessonId) {
                  error_log('Updating lesson ' . $lessonId . ' to order ' . ($index + 1));
                  Lesson::updateOrder($lessonId, $index + 1);
              }
              $success = 'Lesson order updated successfully.';
          } else {
              error_log('Invalid data for reordering lessons');
          }
          // Return JSON response for AJAX
          header('Content-Type: application/json');
          echo json_encode(['success' => true, 'message' => $success]);
          exit;
      }
    
    // ============ LESSON ACTIONS ============
     elseif ($action === 'add_lesson') {
         $moduleId = intval($_POST['module_id'] ?? 0);
         $title = sanitize($_POST['title'] ?? '');
         $description = $_POST['description'] ?? '';
         $content = $_POST['content'] ?? '';
         $videoUrl = sanitize($_POST['video_url'] ?? '');
         $videoStart = intval($_POST['video_start'] ?? 0);
         $videoStop = intval($_POST['video_stop'] ?? 0);
         $videoDuration = intval($_POST['video_duration'] ?? 0);
         
         // Generate slug from title (or use default if title is empty)
         $slug = !empty($title) ? strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) : 'lesson-' . time();
         $orderIndex = Lesson::getMaxOrderIndex($moduleId) + 1;
         
         $lessonId = Lesson::create([
             'module_id' => $moduleId,
             'title' => $title,
             'slug' => $slug,
             'description' => $description,
             'content' => $content,
             'video_url' => $videoUrl,
             'video_start' => $videoStart,
             'video_stop' => $videoStop,
             'video_duration' => $videoDuration,
             'order_index' => $orderIndex,
             'is_free' => isset($_POST['is_free']) ? 1 : 0,
             'is_published' => isset($_POST['is_published']) ? 1 : 0
         ]);
        
        if ($lessonId) {
            $success = 'Lesson created successfully.';
            logActivity($_SESSION['admin_id'], 'create_lesson', 'lessons', $lessonId, null, ['title' => $title, 'module_id' => $moduleId]);
        } else {
            $error = 'Failed to create lesson.';
        }
    }
    
     elseif ($action === 'update_lesson') {
         $lessonId = intval($_POST['lesson_id'] ?? 0);
         if ($lessonId > 0) {
             Lesson::update($lessonId, [
                 'title' => sanitize($_POST['title'] ?? ''),
                 'description' => $_POST['description'] ?? '',
                 'content' => $_POST['content'] ?? '',
                 'video_url' => sanitize($_POST['video_url'] ?? ''),
                 'video_start' => intval($_POST['video_start'] ?? 0),
                 'video_stop' => intval($_POST['video_stop'] ?? 0),
                 'video_duration' => intval($_POST['video_duration'] ?? 0),
                 'is_free' => isset($_POST['is_free']) ? 1 : 0,
                 'is_published' => isset($_POST['is_published']) ? 1 : 0
             ]);
            
            // Reload the page to show updated data
            header('Location: ' . $_SERVER['PHP_SELF'] . '?course_id=' . $courseId);
            exit;
        }
    }
    
    elseif ($action === 'delete_lesson') {
        $lessonId = intval($_POST['lesson_id'] ?? 0);
        if ($lessonId > 0) {
            Lesson::delete($lessonId);
            $success = 'Lesson deleted successfully.';
        }
    }
    
    // ============ LESSON RESOURCE ACTIONS ============
     elseif ($action === 'add_resource') {
        // Debug: Log all POST and FILES data
        error_log('add_resource POST: ' . print_r($_POST, true));
        error_log('add_resource FILES: ' . print_r($_FILES, true));
        
        $lessonId = intval($_POST['lesson_id'] ?? 0);
        $title = sanitize($_POST['resource_title'] ?? '');
        
        // Handle file upload
        $filePath = '';
        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/resources/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
                error_log('Created upload directory: ' . $uploadDir);
            }
            
            $fileName = time() . '_' . basename($_FILES['resource_file']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $targetPath)) {
                $filePath = '/uploads/resources/' . $fileName;
                error_log('File uploaded successfully: ' . $filePath);
            } else {
                error_log('Failed to move uploaded file: ' . $_FILES['resource_file']['tmp_name'] . ' to ' . $targetPath);
                error_log('Error: ' . $_FILES['resource_file']['error']);
            }
        }
        
        $resourceUrl = sanitize($_POST['resource_url'] ?? '');
        $resourceType = sanitize($_POST['resource_type'] ?? 'document');
        
        error_log('add_resource - filePath: ' . $filePath . ', title: ' . $title . ', url: ' . $resourceUrl);
        
        if ($filePath || !empty($resourceUrl) || !empty($title)) {
            $resourceData = [
                'lesson_id' => $lessonId,
                'title' => $title,
                'file_path' => $filePath ?: $resourceUrl,
                'file_type' => $resourceType
            ];
            
            error_log('Creating resource with data: ' . print_r($resourceData, true));
            
            $resourceId = LessonResource::create($resourceData);
            
            error_log('Resource created with ID: ' . $resourceId);
            
            // Check if resource was created successfully
            if ($resourceId) {
                // Return success response in JSON format
                header('Content-Type: application/json');
                $resource = LessonResource::getById($resourceId);
                error_log('Resource data from DB: ' . print_r($resource, true));
                echo json_encode([
                    'success' => true,
                    'message' => 'Resource added successfully.',
                    'resource' => $resource
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to create resource in database.'
                ]);
            }
            exit;
        } else {
            $error = 'Please provide at least a title, file, or URL.';
            error_log('Resource creation failed - missing required fields');
            
            // Return error response in JSON format
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $error
            ]);
            exit;
        }
    }
    
     elseif ($action === 'delete_resource') {
         $resourceId = intval($_POST['resource_id'] ?? 0);
         if ($resourceId > 0) {
             $resource = LessonResource::getById($resourceId);
             if ($resource && !empty($resource['file_path'])) {
                 $filePath = '../' . $resource['file_path'];
                 if (file_exists($filePath)) {
                     unlink($filePath);
                 }
             }
             LessonResource::delete($resourceId);
             // Return success response in JSON format
             header('Content-Type: application/json');
             echo json_encode([
                 'success' => true,
                 'message' => 'Resource deleted successfully.'
             ]);
         } else {
             header('Content-Type: application/json');
             echo json_encode([
                 'success' => false,
                 'error' => 'Invalid resource ID'
             ]);
         }
         exit;
     }
    
    // ============ QUIZ ACTIONS ============
    elseif ($action === 'add_quiz') {
        $moduleId = intval($_POST['module_id'] ?? 0);
        $lessonId = intval($_POST['lesson_id'] ?? 0);
        $title = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        
        Quiz::create([
            'course_id' => $courseId,
            'module_id' => $moduleId > 0 ? $moduleId : null,
            'lesson_id' => $lessonId > 0 ? $lessonId : null,
            'title' => $title,
            'description' => $description,
            'time_limit' => intval($_POST['time_limit'] ?? 0),
            'passing_score' => floatval($_POST['passing_score'] ?? 70),
            'max_attempts' => intval($_POST['max_attempts'] ?? 0),
            'is_final' => isset($_POST['is_final']) ? 1 : 0,
            'published' => isset($_POST['published']) ? 1 : 0
        ]);
        $success = 'Quiz created successfully.';
    }
    
    elseif ($action === 'update_quiz') {
        $quizId = intval($_POST['quiz_id'] ?? 0);
        if ($quizId > 0) {
            Quiz::update($quizId, [
                'title' => sanitize($_POST['title'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'time_limit' => intval($_POST['time_limit'] ?? 0),
                'passing_score' => floatval($_POST['passing_score'] ?? 70),
                'max_attempts' => intval($_POST['max_attempts'] ?? 0),
                'is_final' => isset($_POST['is_final']) ? 1 : 0,
                'published' => isset($_POST['published']) ? 1 : 0
            ]);
            $success = 'Quiz updated successfully.';
        }
    }
    
    elseif ($action === 'delete_quiz') {
        $quizId = intval($_POST['quiz_id'] ?? 0);
        if ($quizId > 0) {
            Quiz::delete($quizId);
            $success = 'Quiz deleted successfully.';
        }
    }
    
    // ============ QUIZ QUESTION ACTIONS ============
    elseif ($action === 'add_question') {
        $quizId = intval($_POST['quiz_id'] ?? 0);
        $questionText = $_POST['question_text'] ?? '';
        $options = $_POST['options'] ?? [];
        $correctOption = intval($_POST['correct_option'] ?? 0);
        
        $questionId = Quiz::createQuestion([
            'quiz_id' => $quizId,
            'question_type' => sanitize($_POST['question_type'] ?? 'multiple_choice'),
            'question_text' => $questionText,
            'explanation' => sanitize($_POST['explanation'] ?? ''),
            'points' => floatval($_POST['points'] ?? 1),
            'order_index' => count(Quiz::getQuestions($quizId)) + 1
        ]);
        
        // Add options (only if we have them)
        if (!empty($options)) {
            foreach ($options as $index => $optionText) {
                if (!empty(trim($optionText))) {
                    Quiz::createOption([
                        'question_id' => $questionId,
                        'option_text' => $optionText,
                        'is_correct' => ($index === $correctOption) ? 1 : 0,
                        'order_index' => $index + 1
                    ]);
                }
            }
        }
        $success = 'Question added successfully.';
    }
    
    elseif ($action === 'delete_question') {
        $questionId = intval($_POST['question_id'] ?? 0);
        if ($questionId > 0) {
            Quiz::deleteQuestion($questionId);
            $success = 'Question deleted successfully.';
        }
    }
    
    elseif ($action === 'reorder_quizzes') {
        // Handle quiz reordering
        $quizOrders = $_POST['quiz_order'] ?? [];
        if (!empty($quizOrders)) {
            foreach ($quizOrders as $index => $quizId) {
                Quiz::updateOrder($quizId, $index + 1);
            }
            $success = 'Quiz order updated successfully.';
        }
    }
    
    elseif ($action === 'reorder_questions') {
        // Handle question reordering
        $quizId = intval($_POST['quiz_id'] ?? 0);
        $questionOrders = $_POST['question_order'] ?? [];
        if (!empty($questionOrders)) {
            foreach ($questionOrders as $index => $questionId) {
                Quiz::updateQuestionOrder($questionId, $index + 1);
            }
            $success = 'Question order updated successfully.';
        }
    }
}

// Get course modules with lessons
$modules = Module::getAllByCourse($courseId);

// Get all lessons for the course (for quiz assignment)
$allLessons = [];
foreach ($modules as $module) {
    $lessons = Lesson::getAllByModule($module['id']);
    $allLessons = array_merge($allLessons, $lessons);
}

// Get quizzes for this course
$quizzes = Quiz::getByCourse($courseId);

define('PAGE_TITLE', 'Manage Course: ' . $course['title']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/styles.css">
    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
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
            text-decoration: none;
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
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }
        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
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
        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { background: var(--gray-200); color: var(--gray-700); }
        .btn-danger { background: var(--danger); color: white; }
        .btn-sm { padding: 0.5rem 0.875rem; font-size: 0.8125rem; }
        .btn-success { background: #10b981; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; }
        .btn-warning { background: #f59e0b; color: white; }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }
        .alert-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); }
        .alert-success { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
        
        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--gray-200);
            padding-bottom: 0;
        }
        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: var(--gray-500);
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: var(--transition);
        }
        .tab:hover { color: var(--gray-700); }
        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-body {
            padding: 1.5rem;
        }
        
        /* Drag and Drop Styles */
        .drag-handle {
            cursor: grab;
            padding: 0.5rem;
            color: var(--gray-400);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            transition: all 0.2s ease;
            user-select: none;
        }
        .drag-handle:active { cursor: grabbing; }
        .drag-handle:hover { color: var(--primary); }
        .draggable-list { list-style: none; padding: 0; margin: 0; }
        .draggable-item {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            background: white;
            transition: all 0.2s ease;
            user-select: none;
        }
        .draggable-item.dragging {
            opacity: 0.5;
            border: 2px dashed var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        .draggable-item.drag-over {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.1);
        }
        .draggable-item.reorder-mode {
            cursor: default;
        }
        .draggable-item.reorder-mode .drag-handle {
            cursor: grab;
        }
        
        /* Module accordion */
        .module-item {
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        .module-item.dragging {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: rotate(2deg);
        }
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--gray-50);
            cursor: pointer;
        }
        .module-header:hover { background: var(--gray-100); }
        .module-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .module-title svg {
            width: 20px;
            height: 20px;
            color: var(--gray-400);
            transition: transform 0.2s ease;
        }
        .module-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .module-content {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }
        
        /* Lesson list */
        .lesson-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .lesson-item:last-child { border-bottom: none; }
        .lesson-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
        }
        .lesson-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Modal */
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
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 700px;
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
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
        }
        .modal-body { padding: 1.5rem; }
        
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--gray-700); }
        .form-input, .form-select, .form-textarea, .form-number {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-textarea { min-height: 120px; resize: vertical; }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        /* Quiz styles */
        .quiz-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            margin-bottom: 0.75rem;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        
        .question-item {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .question-text {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .options-list {
            list-style: none;
            padding: 0;
        }
        .options-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .options-list li.correct {
            color: #059669;
            font-weight: 500;
        }
        
        /* Resource list */
        .resource-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 1024px) {
            .admin-page { grid-template-columns: 1fr; }
            .admin-sidebar { display: none; }
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
            <div class="breadcrumb">
                <a href="<?= APP_URL ?>/admin/courses.php">Courses</a>
                <span>/</span>
                <span>Manage</span>
            </div>
            
            <div class="page-header">
                <div>
                    <h1><?= sanitize($course['title']) ?></h1>
                    <p style="color: var(--gray-500);"><?= ucfirst($course['category']) ?> | <?= ucfirst($course['level']) ?> | $<?= number_format($course['price'], 2) ?></p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <button class="btn btn-warning" id="reorderBtn" onclick="toggleReorderMode()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        Rearrange
                    </button>
                    <a href="<?= APP_URL ?>/admin/courses.php" class="btn btn-secondary">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back to Courses
                    </a>
                </div>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('modules')">Modules & Lessons</button>
                <button class="tab" onclick="switchTab('quizzes')">Quizzes</button>
                <button class="tab" onclick="switchTab('resources')">Resources</button>
            </div>
            
            <!-- Modules & Lessons Tab -->
            <div id="modules" class="tab-content active">
                <div class="card">
                    <div class="card-header">
                        <h3>Modules & Lessons</h3>
                        <?php if (isAdminUser()): ?>
                        <button class="btn btn-primary btn-sm" onclick="openModal('addModule')">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Module
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (empty($modules)): ?>
                            <p style="color: var(--gray-500); text-align: center; padding: 2rem;">No modules yet. Click "Add Module" to create one.</p>
                        <?php else: ?>
                            <!-- Modules List with Drag & Drop -->
                            <ul class="draggable-list" id="modulesList">
                                <?php foreach ($modules as $index => $module): ?>
                                <li class="draggable-item" draggable="true" data-module-id="<?= $module['id'] ?>" data-order="<?= $index ?>">
                                    <div class="module-header" onclick="toggleModule(<?= $module['id'] ?>)">
                                        <div class="module-title">
                                            <span class="drag-handle" onclick="event.stopPropagation()" title="Drag to reorder">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                            </span>
                                            <svg class="module-arrow" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            <span><?= sanitize($module['title']) ?></span>
                                            <span class="badge <?= $module['is_published'] ? 'badge-success' : 'badge-warning' ?>">
                                                <?= $module['is_published'] ? 'Published' : 'Draft' ?>
                                            </span>
                                        </div>
                                        <div class="module-actions">
                                            <span style="color: var(--gray-500); font-size: 0.875rem;">
                                                <?= Module::getLessonCount($module['id']) ?> lessons
                                            </span>
                                            <?php if (isAdminUser()): ?>
                                            <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); openModal('editModule', <?= $module['id'] ?>)">Edit</button>
                                            <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteModule(<?= $module['id'] ?>)">Delete</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                     <div class="module-content" id="module-content-<?= $module['id'] ?>" style="display: none;">
                                        <?php $lessons = Lesson::getAllByModule($module['id']); ?>
                                        <?php if (empty($lessons)): ?>
                                            <p style="color: var(--gray-500); text-align: center; padding: 1rem;">No lessons yet.</p>
                                        <?php else: ?>
                                            <ul class="draggable-list" id="lessonsList-<?= $module['id'] ?>">
                                                <?php foreach ($lessons as $lesson): ?>
                                                <li class="draggable-item lesson-item" draggable="true" data-lesson-id="<?= $lesson['id'] ?>" data-module-id="<?= $module['id'] ?>">
                                                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                                        <div class="lesson-title">
                                                            <span class="drag-handle" onclick="event.stopPropagation()" title="Drag to reorder">
                                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                            </span>
                                                            <div class="lesson-icon">
                                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            </div>
                                                            <div>
                                                                <div><?= sanitize($lesson['title']) ?></div>
                                                                <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                                    <?= $lesson['video_url'] ? 'Video' : 'Text' ?> 
                                                                    <?= $lesson['is_free'] ? '| Free Preview' : '' ?>
                                                                    | <?= $lesson['is_published'] ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-warning">Draft</span>' ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="lesson-actions">
                                                            <?php if (isAdminUser()): ?>
                                                            <button class="btn btn-secondary btn-sm" onclick="openModal('editLesson', <?= $lesson['id'] ?>)">Edit</button>
                                                            <button class="btn btn-danger btn-sm" onclick="deleteLesson(<?= $lesson['id'] ?>)">Delete</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            
                                            <!-- Hidden form for reordering lessons -->
                                            <form method="POST" id="reorderLessonsForm-<?= $module['id'] ?>" style="display: none;">
                                                <input type="hidden" name="action" value="reorder_lessons">
                                                <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                                                <?php foreach ($lessons as $index => $lesson): ?>
                                                <input type="hidden" name="lesson_order[<?= $index ?>]" value="<?= $lesson['id'] ?>" id="lesson_order_<?= $module['id'] ?>_<?= $index ?>">
                                                <?php endforeach; ?>
                                            </form>
                                        <?php endif; ?>
                                        
                                         <?php if (isAdminUser()): ?>
                                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200); display: flex; gap: 0.75rem;">
                                             <button class="btn btn-warning btn-sm" id="reorderLessonsBtn-<?= $module['id'] ?>" onclick="toggleReorderMode('lessons', <?= $module['id'] ?>)">
                                                 <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                 Rearrange Lessons
                                             </button>
                                            <button class="btn btn-primary btn-sm" onclick="openModal('addLesson', <?= $module['id'] ?>)">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                Add Lesson
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <!-- Hidden form for reordering -->
                            <form method="POST" id="reorderForm" style="display: none;">
                                <input type="hidden" name="action" value="reorder_modules">
                                <?php foreach ($modules as $index => $module): ?>
                                <input type="hidden" name="module_order[<?= $index ?>]" value="<?= $module['id'] ?>" id="module_order_<?= $index ?>">
                                <?php endforeach; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quizzes Tab -->
            <div id="quizzes" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3>Course Quizzes</h3>
                        <?php if (isAdminUser()): ?>
                        <button class="btn btn-primary btn-sm" onclick="openModal('addQuiz')">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Quiz
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (empty($quizzes)): ?>
                            <p style="color: var(--gray-500); text-align: center; padding: 2rem;">No quizzes yet. Click "Add Quiz" to create one.</p>
                        <?php else: ?>
                            <!-- Quizzes List with Drag & Drop -->
                            <ul class="draggable-list" id="quizzesList">
                                <?php foreach ($quizzes as $index => $quiz): ?>
                                <li class="draggable-item quiz-container" draggable="true" data-quiz-id="<?= $quiz['id'] ?>" data-order="<?= $index ?>">
                                    <div class="module-header" onclick="toggleQuiz(<?= $quiz['id'] ?>)">
                                        <div class="module-title">
                                            <span class="drag-handle" onclick="event.stopPropagation()" title="Drag to reorder">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                            </span>
                                            <svg class="quiz-arrow" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            <span><?= sanitize($quiz['title']) ?></span>
                                            <span class="badge <?= $quiz['published'] ? 'badge-success' : 'badge-warning' ?>">
                                                <?= $quiz['published'] ? 'Published' : 'Draft' ?>
                                            </span>
                                            <?= $quiz['is_final'] ? '<span class="badge badge-info">Final Exam</span>' : '' ?>
                                        </div>
                                        <div class="module-actions">
                                            <span style="color: var(--gray-500); font-size: 0.875rem;">
                                                <?= count(Quiz::getQuestions($quiz['id'])) ?> questions
                                            </span>
                                            <?php if (isAdminUser()): ?>
                                            <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); openModal('editQuiz', <?= $quiz['id'] ?>)">Edit</button>
                                            <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); openModal('addQuestion', <?= $quiz['id'] ?>)">Add Question</button>
                                            <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); viewQuizResults(<?= $quiz['id'] ?>)">Results</button>
                                            <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); deleteQuiz(<?= $quiz['id'] ?>)">Delete</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="module-content" id="quiz-content-<?= $quiz['id'] ?>" style="display: none;">
                                        <?php $questions = Quiz::getQuestions($quiz['id']); ?>
                                        <?php if (empty($questions)): ?>
                                            <p style="color: var(--gray-500); text-align: center; padding: 1rem;">No questions yet.</p>
                                        <?php else: ?>
                                            <!-- Questions List with Drag & Drop -->
                                            <ul class="draggable-list" id="questionsList-<?= $quiz['id'] ?>">
                                                <?php foreach ($questions as $qIndex => $question): ?>
                                                <li class="draggable-item question-item" draggable="true" data-question-id="<?= $question['id'] ?>" data-quiz-id="<?= $quiz['id'] ?>" data-order="<?= $qIndex ?>">
                                                    <div class="question-header">
                                                        <div class="question-text">
                                                            <span class="drag-handle" onclick="event.stopPropagation()" title="Drag to reorder">
                                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                            </span>
                                                            Q<?= $question['order_index'] ?>. <?= sanitize($question['question_text']) ?>
                                                        </div>
                                                        <?php if (isAdminUser()): ?>
                                                        <button class="btn btn-danger btn-sm" onclick="deleteQuestion(<?= $question['id'] ?>)">Delete</button>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php $options = Quiz::getOptions($question['id']); ?>
                                                    <?php if (!empty($options)): ?>
                                                    <ul class="options-list">
                                                        <?php foreach ($options as $index => $option): ?>
                                                        <li class="<?= $option['is_correct'] ? 'correct' : '' ?>">
                                                            <?= chr(65 + $index) ?>. <?= sanitize($option['option_text']) ?>
                                                            <?php if ($option['is_correct']): ?>
                                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            <?php endif; ?>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <?php endif; ?>
                                                    <?php if ($question['explanation']): ?>
                                                    <div style="font-size: 0.875rem; color: var(--gray-600); margin-top: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: var(--radius);">
                                                        <strong>Explanation:</strong> <?= sanitize($question['explanation']) ?>
                                                    </div>
                                                    <?php endif; ?>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            
                                            <!-- Hidden form for reordering questions -->
                                            <form method="POST" id="reorderQuestionsForm-<?= $quiz['id'] ?>" style="display: none;">
                                                <input type="hidden" name="action" value="reorder_questions">
                                                <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                                                <?php foreach ($questions as $qIndex => $question): ?>
                                                <input type="hidden" name="question_order[<?= $qIndex ?>]" value="<?= $question['id'] ?>" id="question_order_<?= $quiz['id'] ?>_<?= $qIndex ?>">
                                                <?php endforeach; ?>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if (isAdminUser()): ?>
                                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                                            <button class="btn btn-primary btn-sm" onclick="openModal('addQuestion', <?= $quiz['id'] ?>)">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                Add Question
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <!-- Hidden form for reordering quizzes -->
                            <form method="POST" id="reorderQuizzesForm" style="display: none;">
                                <input type="hidden" name="action" value="reorder_quizzes">
                                <?php foreach ($quizzes as $index => $quiz): ?>
                                <input type="hidden" name="quiz_order[<?= $index ?>]" value="<?= $quiz['id'] ?>" id="quiz_order_<?= $index ?>">
                                <?php endforeach; ?>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Resources Tab -->
            <div id="resources" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h3>Course Resources</h3>
                    </div>
                    <div class="card-body">
                        <p style="color: var(--gray-500); text-align: center; padding: 2rem;">
                            Resources are attached to individual lessons. Go to "Modules & Lessons" tab and click "Edit" on a lesson to manage its resources.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add Module Modal -->
    <div class="modal" id="addModuleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Module</h2>
                <button class="modal-close" onclick="closeModal('addModule')">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_module">
                    <div class="form-group">
                        <label class="form-label">Module Title</label>
                        <input type="text" name="title" class="form-input" placeholder="Enter module title">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="addModuleDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="addModuleDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_published" value="1" checked>
                            <span>Publish immediately</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addModule')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Module</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Module Modal -->
    <div class="modal" id="editModuleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Module</h2>
                <button class="modal-close" onclick="closeModal('editModule')">&times;</button>
            </div>
            <form method="POST" id="editModuleForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_module">
                    <input type="hidden" name="module_id" id="editModuleId">
                    <div class="form-group">
                        <label class="form-label">Module Title</label>
                        <input type="text" name="title" id="editModuleTitle" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="editModuleDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="editModuleDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_published" id="editModulePublished" value="1">
                            <span>Published</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModule')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Module Confirmation -->
    <div class="modal" id="deleteModuleModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Module</h2>
                <button class="modal-close" onclick="closeModal('deleteModule')">&times;</button>
            </div>
            <form method="POST" id="deleteModuleForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete_module">
                    <input type="hidden" name="module_id" id="deleteModuleId">
                    <p>Are you sure you want to delete this module? All lessons and their content will also be deleted. This action cannot be undone.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModule')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Module</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add Lesson Modal -->
    <div class="modal" id="addLessonModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Lesson</h2>
                <button class="modal-close" onclick="closeModal('addLesson')">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_lesson">
                    <input type="hidden" name="module_id" id="addLessonModuleId">
                    <div class="form-group">
                        <label class="form-label">Lesson Title</label>
                        <input type="text" name="title" class="form-input" placeholder="Enter lesson title">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="addLessonDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="addLessonDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="addLessonHasVideo" onchange="toggleVideoFields('add')">
                            <span>Add Video</span>
                        </label>
                    </div>
                    <div id="addLessonVideoFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">YouTube Video URL</label>
                            <input type="text" name="video_url" class="form-input" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Time (seconds)</label>
                            <input type="number" name="video_start" class="form-input" min="0" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stop Time (seconds)</label>
                            <input type="number" name="video_stop" class="form-input" min="0" placeholder="Leave blank for full video">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lesson Content</label>
                        <div id="addLessonContent" class="quill-editor" style="min-height: 200px;"></div>
                        <input type="hidden" name="content" id="addLessonContentHidden">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_free" value="1">
                            <span>Free preview (available without enrollment)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_published" value="1" checked>
                            <span>Publish immediately</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addLesson')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Lesson</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Lesson Modal -->
    <div class="modal" id="editLessonModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Lesson</h2>
                <button class="modal-close" onclick="closeModal('editLesson')">&times;</button>
            </div>
            <form method="POST" id="editLessonForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_lesson">
                    <input type="hidden" name="lesson_id" id="editLessonId">
                    <div class="form-group">
                        <label class="form-label">Lesson Title</label>
                        <input type="text" name="title" id="editLessonTitle" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="editLessonDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="editLessonDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="editLessonHasVideo" onchange="toggleVideoFields('edit')">
                            <span>Add Video</span>
                        </label>
                    </div>
                    <div id="editLessonVideoFields" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">YouTube Video URL</label>
                            <input type="text" name="video_url" id="editLessonVideoUrl" class="form-input" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Time (seconds)</label>
                            <input type="number" name="video_start" id="editLessonVideoStart" class="form-input" min="0" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stop Time (seconds)</label>
                            <input type="number" name="video_stop" id="editLessonVideoStop" class="form-input" min="0" placeholder="Leave blank for full video">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Video Duration (seconds)</label>
                            <input type="number" name="video_duration" id="editLessonVideoDuration" class="form-input" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lesson Content</label>
                        <div id="editLessonContent" class="quill-editor" style="min-height: 200px;"></div>
                        <input type="hidden" name="content" id="editLessonContentHidden">
                    </div>
                    
                    <!-- Lesson Resources -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                        <h4 style="margin-bottom: 1rem;">Lesson Resources</h4>
                        <div id="lessonResourcesList"></div>
                        
                        <div style="margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius);">
                            <h5 style="font-size: 0.875rem; margin-bottom: 0.75rem;">Add Resource</h5>
                            <div id="addResourceForm">
                                <input type="hidden" id="resourceLessonId">
                                <div class="form-group">
                                    <label class="form-label">Resource Title</label>
                                    <input type="text" id="resourceTitle" class="form-input" placeholder="e.g., Exercise File">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">File Type</label>
                                    <select id="resourceType" class="form-select">
                                        <option value="document">Document</option>
                                        <option value="spreadsheet">Spreadsheet</option>
                                        <option value="pdf">PDF</option>
                                        <option value="video">Video</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" id="resourceFile" class="form-input">
                                </div>
                                <div style="text-align: center; margin: 0.5rem 0;">OR</div>
                                <div class="form-group">
                                    <label class="form-label">Resource URL</label>
                                    <input type="url" id="resourceUrl" class="form-input" placeholder="https://...">
                                </div>
                                <button type="button" class="btn btn-success btn-sm" onclick="addResource()">Add Resource</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_free" id="editLessonIsFree" value="1">
                            <span>Free preview</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_published" id="editLessonIsPublished" value="1">
                            <span>Published</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-danger" onclick="deleteLessonFromEdit()">Delete Lesson</button>
                    <div style="display: flex; gap: 0.75rem;">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('editLesson')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add Quiz Modal -->
    <div class="modal" id="addQuizModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Quiz</h2>
                <button class="modal-close" onclick="closeModal('addQuiz')">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_quiz">
                    <div class="form-group">
                        <label class="form-label">Quiz Title</label>
                        <input type="text" name="title" class="form-input" placeholder="Enter quiz title">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="addQuizDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="addQuizDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Module (optional)</label>
                        <select name="module_id" class="form-select">
                            <option value="">No specific module (general quiz)</option>
                            <?php foreach ($modules as $module): ?>
                            <option value="<?= $module['id'] ?>"><?= sanitize($module['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lesson (optional)</label>
                        <select name="lesson_id" class="form-select">
                            <option value="">No specific lesson</option>
                            <?php foreach ($allLessons as $lesson): ?>
                            <option value="<?= $lesson['id'] ?>"><?= sanitize($lesson['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (minutes, 0 = unlimited)</label>
                        <input type="number" name="time_limit" class="form-input" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Passing Score (%)</label>
                        <input type="number" name="passing_score" class="form-input" min="0" max="100" value="70">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Attempts (0 = unlimited)</label>
                        <input type="number" name="max_attempts" class="form-input" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_final" value="1">
                            <span>This is a final exam</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="published" value="1" checked>
                            <span>Publish immediately</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuiz')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Quiz</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Quiz Modal -->
    <div class="modal" id="editQuizModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Quiz</h2>
                <button class="modal-close" onclick="closeModal('editQuiz')">&times;</button>
            </div>
            <form method="POST" id="editQuizForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_quiz">
                    <input type="hidden" name="quiz_id" id="editQuizId">
                    <div class="form-group">
                        <label class="form-label">Quiz Title</label>
                        <input type="text" name="title" id="editQuizTitle" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div id="editQuizDescription" class="quill-editor"></div>
                        <input type="hidden" name="description" id="editQuizDescriptionHidden">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Time Limit (minutes, 0 = unlimited)</label>
                        <input type="number" name="time_limit" id="editQuizTimeLimit" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Passing Score (%)</label>
                        <input type="number" name="passing_score" id="editQuizPassingScore" class="form-input" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Attempts (0 = unlimited)</label>
                        <input type="number" name="max_attempts" id="editQuizMaxAttempts" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_final" id="editQuizIsFinal" value="1">
                            <span>This is a final exam</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="published" id="editQuizPublished" value="1">
                            <span>Published</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-danger" onclick="deleteQuizFromEdit()">Delete Quiz</button>
                    <div style="display: flex; gap: 0.75rem;">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('editQuiz')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Add Question Modal -->
    <div class="modal" id="addQuestionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Quiz Question</h2>
                <button class="modal-close" onclick="closeModal('addQuestion')">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_question">
                    <input type="hidden" name="quiz_id" id="addQuestionQuizId">
                    <div class="form-group">
                        <label class="form-label">Question Type</label>
                        <select name="question_type" class="form-select">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Question Text</label>
                        <div id="addQuestionText" class="quill-editor"></div>
                        <input type="hidden" name="question_text" id="addQuestionTextHidden">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Options *</label>
                        <div id="optionsContainer">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="radio" name="correct_option" value="0" checked>
                                <input type="text" name="options[]" class="form-input" placeholder="Option A">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="radio" name="correct_option" value="1">
                                <input type="text" name="options[]" class="form-input" placeholder="Option B">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="radio" name="correct_option" value="2">
                                <input type="text" name="options[]" class="form-input" placeholder="Option C">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="radio" name="correct_option" value="3">
                                <input type="text" name="options[]" class="form-input" placeholder="Option D">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Explanation (shown after answering)</label>
                        <div id="addQuestionExplanation" class="quill-editor"></div>
                        <input type="hidden" name="explanation" id="addQuestionExplanationHidden">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Points</label>
                        <input type="number" name="points" class="form-input" value="1" min="0.5" step="0.5">
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addQuestion')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Quiz Confirmation -->
    <div class="modal" id="deleteQuizModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Quiz</h2>
                <button class="modal-close" onclick="closeModal('deleteQuiz')">&times;</button>
            </div>
            <form method="POST" id="deleteQuizForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete_quiz">
                    <input type="hidden" name="quiz_id" id="deleteQuizId">
                    <p>Are you sure you want to delete this quiz? All questions and results will also be deleted. This action cannot be undone.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteQuiz')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Quiz</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Module data for JavaScript
         const modulesData = <?= json_encode($modules) ?>;
        const lessonsData = {};
        const quizzesData = <?= json_encode(array_combine(array_column($quizzes, 'id'), $quizzes)) ?>;
        const questionsData = {};
         let isReorderMode = false;
        let currentReorderType = null; // 'modules', 'quizzes', 'questions', or 'lessons'
        let currentActiveTab = 'modules'; // Track current active tab
        let moduleId = null; // Track module ID for lesson reordering
        
        <?php foreach ($modules as $module): ?>
        lessonsData[<?= $module['id'] ?>] = <?= json_encode(Lesson::getAllByModule($module['id'])) ?>;
        <?php endforeach; ?>
        
        <?php foreach ($quizzes as $quiz): ?>
        questionsData[<?= $quiz['id'] ?>] = <?= json_encode(Quiz::getQuestions($quiz['id'])) ?>;
        <?php endforeach; ?>
        
        function switchTab(tabId) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById(tabId).classList.add('active');
            currentActiveTab = tabId; // Update current active tab
            
            // If in reorder mode, switch reorder type to match new tab
            if (isReorderMode) {
                const newType = tabId === 'modules' ? 'modules' : tabId === 'quizzes' ? 'quizzes' : null;
                if (newType && newType !== currentReorderType) {
                    currentReorderType = newType;
                    const btn = document.getElementById('reorderBtn');
                    const allItems = document.querySelectorAll('.draggable-item');
                    allItems.forEach(item => {
                        if (newType === 'modules' && item.dataset.moduleId) {
                            item.classList.add('reorder-mode');
                        } else if (newType === 'quizzes' && item.dataset.quizId) {
                            item.classList.add('reorder-mode');
                        } else if (newType === 'questions' && item.dataset.questionId) {
                            item.classList.add('reorder-mode');
                        } else {
                            item.classList.remove('reorder-mode');
                        }
                    });
                }
            }
        }
        
         function toggleModule(moduleId) {
            // Don't toggle if in reorder mode for modules
            if (isReorderMode && currentReorderType === 'modules') return;
            
            const content = document.getElementById('module-content-' + moduleId);
            const arrow = content.previousElementSibling.querySelector('.module-arrow');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                arrow.style.transform = 'rotate(90deg)';
                // Save expanded state to localStorage
                saveExpandedModules();
            } else {
                content.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
                // Save expanded state to localStorage
                saveExpandedModules();
            }
        }
        
        function saveExpandedModules() {
            const expandedModules = [];
            document.querySelectorAll('.module-content').forEach(content => {
                if (content.style.display === 'block') {
                    const moduleId = content.id.replace('module-content-', '');
                    expandedModules.push(moduleId);
                }
            });
            localStorage.setItem('expandedModules_' + <?= $courseId ?>, JSON.stringify(expandedModules));
        }
        
        function loadExpandedModules() {
            const savedModules = localStorage.getItem('expandedModules_' + <?= $courseId ?>);
            if (savedModules) {
                const expandedModules = JSON.parse(savedModules);
                expandedModules.forEach(moduleId => {
                    const content = document.getElementById('module-content-' + moduleId);
                    if (content) {
                        content.style.display = 'block';
                        const arrow = content.previousElementSibling.querySelector('.module-arrow');
                        if (arrow) {
                            arrow.style.transform = 'rotate(90deg)';
                        }
                    }
                });
            }
        }
        
        function toggleQuiz(quizId) {
            // Don't toggle if in reorder mode for quizzes
            if (isReorderMode && currentReorderType === 'quizzes') return;
            
            const content = document.getElementById('quiz-content-' + quizId);
            const arrow = content.previousElementSibling.querySelector('.quiz-arrow');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                arrow.style.transform = 'rotate(90deg)';
            } else {
                content.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
          function toggleReorderMode(type, moduleId = null) {
             // For lessons, we need to handle module-specific state
             if (type === 'lessons') {
                 const btn = document.getElementById('reorderLessonsBtn-' + moduleId);
                 const lessonsList = document.getElementById('lessonsList-' + moduleId);
                 const items = lessonsList ? lessonsList.querySelectorAll('.draggable-item') : [];
                 
                 // Check if we're in lesson reorder mode for this module
                 const isLessonReorderMode = btn.classList.contains('btn-success');
                 
                 if (isLessonReorderMode) {
                     // Exit lesson reorder mode
                     btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg> Rearrange Lessons';
                     btn.classList.remove('btn-success');
                     btn.classList.add('btn-warning');
                     items.forEach(item => {
                         item.classList.remove('reorder-mode');
                     });
                     // If this was the only reorder mode active, reset global state
                     if (currentReorderType === 'lessons') {
                         isReorderMode = false;
                         currentReorderType = null;
                     }
                 } else {
                     // Enter lesson reorder mode
                     btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Done Rearranging';
                     btn.classList.remove('btn-warning');
                     btn.classList.add('btn-success');
                     items.forEach(item => {
                         item.classList.add('reorder-mode');
                     });
                     // Set global state
                     isReorderMode = true;
                     currentReorderType = 'lessons';
                     // Make sure other reorder modes are disabled
                     const mainBtn = document.getElementById('reorderBtn');
                     if (mainBtn.classList.contains('btn-success')) {
                         mainBtn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg> Rearrange';
                         mainBtn.classList.remove('btn-success');
                         mainBtn.classList.add('btn-warning');
                         document.querySelectorAll('.draggable-item').forEach(item => {
                             if (!item.dataset.lessonId) {
                                 item.classList.remove('reorder-mode');
                             }
                         });
                     }
                 }
             } else {
                 // Original logic for modules, quizzes, and questions
                 const reorderType = type || (currentActiveTab === 'modules' ? 'modules' : currentActiveTab === 'quizzes' ? 'quizzes' : null);
                 
                 if (isReorderMode && currentReorderType === reorderType) {
                     isReorderMode = false;
                     currentReorderType = null;
                 } else {
                     isReorderMode = true;
                     currentReorderType = reorderType;
                 }
                 
                 const btn = document.getElementById('reorderBtn');
                 const allItems = document.querySelectorAll('.draggable-item');
                 
                 if (isReorderMode) {
                     btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Done Rearranging';
                     btn.classList.remove('btn-warning');
                     btn.classList.add('btn-success');
                     
                     allItems.forEach(item => {
                         if (type === 'modules' && item.dataset.moduleId) {
                             item.classList.add('reorder-mode');
                         } else if (type === 'quizzes' && item.dataset.quizId) {
                             item.classList.add('reorder-mode');
                         } else if (type === 'questions' && item.dataset.questionId) {
                             item.classList.add('reorder-mode');
                         } else if (type === 'lessons' && item.dataset.lessonId) {
                             item.classList.add('reorder-mode');
                         } else {
                             item.classList.remove('reorder-mode');
                         }
                     });
                 } else {
                     btn.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg> Rearrange';
                     btn.classList.remove('btn-success');
                     btn.classList.add('btn-warning');
                     
                     allItems.forEach(item => {
                         item.classList.remove('reorder-mode');
                     });
                 }
             }
         }
        

        
        function closeModal(type) {
            if (type === 'addModule') document.getElementById('addModuleModal').classList.remove('active');
            else if (type === 'editModule') document.getElementById('editModuleModal').classList.remove('active');
            else if (type === 'deleteModule') document.getElementById('deleteModuleModal').classList.remove('active');
            else if (type === 'addLesson') document.getElementById('addLessonModal').classList.remove('active');
            else if (type === 'editLesson') document.getElementById('editLessonModal').classList.remove('active');
            else if (type === 'addQuiz') document.getElementById('addQuizModal').classList.remove('active');
            else if (type === 'editQuiz') document.getElementById('editQuizModal').classList.remove('active');
            else if (type === 'addQuestion') document.getElementById('addQuestionModal').classList.remove('active');
            else if (type === 'deleteQuiz') document.getElementById('deleteQuizModal').classList.remove('active');
        }
        
        function deleteModule(id) {
            if (confirm('Are you sure you want to delete this module and all its lessons?')) {
                document.getElementById('deleteModuleId').value = id;
                document.getElementById('deleteModuleForm').submit();
            }
        }
        
        function deleteLesson(id) {
            if (confirm('Are you sure you want to delete this lesson?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete_lesson"><input type="hidden" name="lesson_id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteLessonFromEdit() {
            const lessonId = document.getElementById('editLessonId').value;
            if (lessonId && confirm('Are you sure you want to delete this lesson?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete_lesson"><input type="hidden" name="lesson_id" value="' + lessonId + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function deleteQuiz(id) {
            if (confirm('Are you sure you want to delete this quiz and all its questions?')) {
                document.getElementById('deleteQuizId').value = id;
                document.getElementById('deleteQuizForm').submit();
            }
        }
        
        function deleteQuizFromEdit() {
            const quizId = document.getElementById('editQuizId').value;
            if (quizId && confirm('Are you sure you want to delete this quiz?')) {
                document.getElementById('deleteQuizId').value = quizId;
                document.getElementById('deleteQuizForm').submit();
            }
        }
        
        function deleteQuestion(id) {
            if (confirm('Are you sure you want to delete this question?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete_question"><input type="hidden" name="question_id" value="' + id + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }
        
         function deleteResource(id) {
            if (confirm('Are you sure you want to delete this resource?')) {
                const formData = new FormData();
                formData.append('action', 'delete_resource');
                formData.append('resource_id', id);
                
                fetch('', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the resources list to update the UI
                        const lessonId = document.getElementById('resourceLessonId').value;
                        loadLessonResources(lessonId);
                        showSuccessMessage(data.message || 'Resource deleted successfully');
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                }).catch(error => {
                    console.error('Error deleting resource:', error);
                    alert('Error deleting resource. Please try again.');
                });
            }
        }
        
         function addResource() {
             const lessonId = document.getElementById('resourceLessonId').value;
             const title = document.getElementById('resourceTitle').value;
             const type = document.getElementById('resourceType').value;
             const file = document.getElementById('resourceFile').files[0];
             const url = document.getElementById('resourceUrl').value;
             
             console.log('addResource called with:', { lessonId, title, type, file: file ? file.name : 'none', url });
             
             if (!lessonId) {
                 alert('Error: Lesson ID is missing. Please close and reopen the lesson edit form.');
                 console.error('Lesson ID is missing');
                 return;
             }
             
             if (!title || (!file && !url)) {
                 alert('Please provide a title and either a file or URL');
                 return;
             }
             
             const formData = new FormData();
             formData.append('action', 'add_resource');
             formData.append('lesson_id', lessonId);
             formData.append('resource_title', title);
             formData.append('resource_type', type);
             if (file) {
                 formData.append('resource_file', file);
             }
             if (url) {
                 formData.append('resource_url', url);
             }
             
             console.log('Sending request to add resource...');
             
             fetch('', {
                 method: 'POST',
                 body: formData
             }).then(response => {
                 console.log('Response status:', response.status);
                 console.log('Response headers:', [...response.headers.entries()]);
                 return response.text();
             })
             .then(text => {
                 console.log('Raw response text:', text);
                 try {
                     const data = JSON.parse(text);
                     console.log('Parsed JSON:', data);
                     
                     if (data.success) {
                         // Reload the resources list to show the new resource
                         loadLessonResources(lessonId);
                         // Clear the form fields
                         document.getElementById('resourceTitle').value = '';
                         document.getElementById('resourceFile').value = '';
                         document.getElementById('resourceUrl').value = '';
                         // Show success message
                         showSuccessMessage(data.message || 'Resource added successfully');
                     } else {
                         alert('Error: ' + (data.error || 'Unknown error occurred'));
                     }
                 } catch (e) {
                     console.error('Failed to parse JSON:', e);
                     console.error('Response text was:', text);
                     alert('Error: Invalid response from server. Check console for details.');
                 }
             }).catch(error => {
                 console.error('Fetch error:', error);
                 alert('Error adding resource. Please try again.');
             });
         }
        
        function loadLessonResources(lessonId) {
            console.log('Loading resources for lesson:', lessonId);
            // Load lesson resources from the server
            fetch('?action=get_resources&lesson_id=' + lessonId)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Resources data:', data);
                    const resourcesList = document.getElementById('lessonResourcesList');
                    if (!resourcesList) {
                        console.error('lessonResourcesList element not found');
                        return;
                    }
                    
                    if (!data.resources || data.resources.length === 0) {
                        console.log('No resources found, showing empty message');
                        resourcesList.innerHTML = '<p style="color: var(--gray-500); font-size: 0.875rem;">No resources added yet.</p>';
                    } else {
                        console.log('Rendering', data.resources.length, 'resources');
                        resourcesList.innerHTML = data.resources.map(resource => {
                            console.log('Rendering resource:', resource);
                            return `
                            <div class="resource-item">
                                <div>
                                    <strong>${resource.title || 'Untitled'}</strong>
                                    <div style="font-size: 0.875rem; color: var(--gray-500);">
                                        ${resource.file_type || 'document'}
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="${resource.file_path}" target="_blank" class="btn btn-secondary btn-sm">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteResource(${resource.id})">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        `;
                        }).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading resources:', error);
                    const resourcesList = document.getElementById('lessonResourcesList');
                    if (resourcesList) {
                        resourcesList.innerHTML = '<p style="color: var(--danger); font-size: 0.875rem;">Error loading resources. Please try again.</p>';
                    }
                });
        }
        
        function viewQuizResults(quizId) {
            // This would open a modal with quiz results
            alert('Quiz results feature coming soon!');
        }
        
          function toggleVideoFields(formType) {
              const prefix = formType === 'edit' ? 'editLesson' : 'addLesson';
              const hasVideoCheckbox = document.getElementById(prefix + 'HasVideo');
              const videoFieldsDiv = document.getElementById(prefix + 'VideoFields');
              const videoUrlInput = document.getElementById(prefix + 'VideoUrl');
              const videoStartInput = document.getElementById(prefix + 'VideoStart');
              const videoStopInput = document.getElementById(prefix + 'VideoStop');
              const videoDurationInput = document.getElementById(prefix + 'VideoDuration');
              
              if (hasVideoCheckbox.checked) {
                  videoFieldsDiv.style.display = 'block';
              } else {
                  videoFieldsDiv.style.display = 'none';
                  if (videoUrlInput) videoUrlInput.value = '';
                  if (videoStartInput) videoStartInput.value = '';
                  if (videoStopInput) videoStopInput.value = '';
                  if (videoDurationInput) videoDurationInput.value = '';
              }
          }
         
         // Close modals on outside click
         document.querySelectorAll('.modal').forEach(modal => {
             modal.addEventListener('click', function(e) {
                 if (e.target === this) {
                     this.classList.remove('active');
                 }
             });
         });
        
        // =====================
        // Drag and Drop Reordering
        // =====================
        let draggedItem = null;
        
         // Initialize drag events for all lists
        function initDragEvents(listId, type) {
            const draggableList = document.getElementById(listId);
            if (!draggableList) return;
            
            const items = draggableList.querySelectorAll('.draggable-item');
            items.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    if (!isReorderMode || currentReorderType !== type) return;
                    draggedItem = this;
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                    let data = '';
                    if (type === 'modules') {
                        data = this.dataset.moduleId;
                    } else if (type === 'quizzes') {
                        data = this.dataset.quizId;
                    } else if (type === 'questions') {
                        data = this.dataset.questionId;
                    } else if (type === 'lessons') {
                        data = this.dataset.lessonId;
                    }
                    e.dataTransfer.setData('text/plain', data);
                });
                item.addEventListener('dragend', handleDragEnd);
            });
            
            draggableList.addEventListener('dragover', handleDragOver);
            draggableList.addEventListener('drop', function(e) {
                handleDrop(e, type, draggableList);
            });
            draggableList.addEventListener('dragenter', handleDragEnter);
            draggableList.addEventListener('dragleave', handleDragLeave);
        }
        
         function handleDragStart(e, itemId, type) {
            if (!isReorderMode || currentReorderType !== type) return;
            
            draggedItem = document.querySelector(`[data-${type}-id="${itemId}"]`);
            draggedItem.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', itemId);
            
            // If it's a lesson, store the module ID for use in drag end
            if (type === 'lessons') {
                moduleId = draggedItem.dataset.moduleId;
                console.log('Dragging lesson from module:', moduleId);
            }
        }
        
          function handleDragEnd(e) {
            let lessonModuleId = null;
            if (draggedItem) {
                // Get module ID from the dragged item before clearing it
                lessonModuleId = draggedItem.dataset.moduleId;
                draggedItem.classList.remove('dragging');
                draggedItem = null;
            }
            
            // Remove drag-over class from all items
            document.querySelectorAll('.draggable-item').forEach(item => {
                item.classList.remove('drag-over');
            });
            
            // Save the new order
            if (currentReorderType === 'modules') {
                saveModuleOrder();
            } else if (currentReorderType === 'quizzes') {
                saveQuizOrder();
            } else if (currentReorderType === 'questions') {
                // Find which quiz's questions were reordered
                const activeQuiz = document.querySelector('.quiz-container .module-content:not([style*="display: none"])');
                if (activeQuiz) {
                    const quizId = activeQuiz.id.replace('quiz-content-', '');
                    saveQuestionOrder(quizId);
                }
            } else if (currentReorderType === 'lessons') {
                // Get module ID from the dragged item or find active module
                if (lessonModuleId) {
                    console.log('Saving lesson order for module:', lessonModuleId);
                    saveLessonOrder(lessonModuleId);
                } else {
                    const activeModule = document.querySelector('.module-item .module-content:not([style*="display: none"])');
                    if (activeModule) {
                        const moduleId = activeModule.id.replace('module-content-', '');
                        console.log('Saving lesson order for active module:', moduleId);
                        saveLessonOrder(moduleId);
                    }
                }
            }
        }
        
          function saveLessonOrder(moduleId) {
              console.log('saveLessonOrder called for module:', moduleId);
              const draggableList = document.getElementById('lessonsList-' + moduleId);
              if (!draggableList) {
                  console.error('lessonsList-' + moduleId + ' not found');
                  return;
              }
              
              const items = [...draggableList.querySelectorAll('.draggable-item')];
              const lessonOrder = items.map(item => item.dataset.lessonId);
              console.log('Lesson order:', lessonOrder);
              
              const form = document.getElementById('reorderLessonsForm-' + moduleId);
              if (!form) {
                  console.error('reorderLessonsForm-' + moduleId + ' not found');
                  return;
              }
              
              // Update existing inputs or add new ones
              const existingInputs = form.querySelectorAll('input[name^="lesson_order"]');
              
              // Remove any extra inputs
              if (existingInputs.length > lessonOrder.length) {
                  for (let i = lessonOrder.length; i < existingInputs.length; i++) {
                      existingInputs[i].remove();
                  }
              }
              
              // Update or create inputs
              lessonOrder.forEach((lessonId, index) => {
                  let input = document.getElementById('lesson_order_' + moduleId + '_' + index);
                  if (!input) {
                      input = document.createElement('input');
                      input.type = 'hidden';
                      input.name = 'lesson_order[' + index + ']';
                      input.id = 'lesson_order_' + moduleId + '_' + index;
                      form.appendChild(input);
                  }
                  input.value = lessonId;
              });
              
              // Submit the form via fetch for AJAX update
              const formData = new FormData(form);
              console.log('Form data:', [...formData]);
              fetch('', {
                  method: 'POST',
                  body: formData
              }).then(response => {
                  console.log('Response status:', response.status);
                  return response.json();
              }).then(data => {
                  console.log('Response data:', data);
                  showSuccessMessage('Lesson order saved!');
              }).catch(error => {
                  console.error('Error saving lesson order:', error);
              });
          }
        
        function handleDragOver(e) {
            if (!isReorderMode) return;
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }
        
        function handleDragEnter(e) {
            if (!isReorderMode) return;
            e.preventDefault();
            const targetItem = e.target.closest('.draggable-item');
            if (targetItem && targetItem !== draggedItem) {
                targetItem.classList.add('drag-over');
            }
        }
        
        function handleDragLeave(e) {
            const targetItem = e.target.closest('.draggable-item');
            if (targetItem) {
                targetItem.classList.remove('drag-over');
            }
        }
        
        function handleDrop(e, type, list) {
            if (!isReorderMode || currentReorderType !== type) return;
            e.preventDefault();
            e.stopPropagation();
            
            const targetItem = e.target.closest('.draggable-item');
            if (targetItem && targetItem !== draggedItem) {
                // Swap items in the DOM
                const allItems = [...list.querySelectorAll('.draggable-item')];
                const draggedIndex = allItems.indexOf(draggedItem);
                const droppedIndex = allItems.indexOf(targetItem);
                
                if (draggedIndex < droppedIndex) {
                    list.insertBefore(draggedItem, targetItem.nextSibling);
                } else {
                    list.insertBefore(draggedItem, targetItem);
                }
            }
            
            if (targetItem) {
                targetItem.classList.remove('drag-over');
            }
        }
        
        // =====================
        // Module Reordering
        // =====================
        function saveModuleOrder() {
            const draggableList = document.getElementById('modulesList');
            if (!draggableList) return;
            
            const items = [...draggableList.querySelectorAll('.draggable-item')];
            const moduleOrder = items.map(item => item.dataset.moduleId);
            
            // Update hidden inputs with new order
            moduleOrder.forEach((moduleId, index) => {
                const input = document.getElementById('module_order_' + index);
                if (input) {
                    input.value = moduleId;
                }
            });
            
            // Submit the form via fetch for AJAX update
            const formData = new FormData(document.getElementById('reorderForm'));
            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                showSuccessMessage('Module order saved!');
            }).catch(error => {
                console.error('Error saving module order:', error);
            });
        }
        
        // =====================
        // Quiz Reordering
        // =====================
        function saveQuizOrder() {
            const draggableList = document.getElementById('quizzesList');
            if (!draggableList) return;
            
            const items = [...draggableList.querySelectorAll('.draggable-item')];
            const quizOrder = items.map(item => item.dataset.quizId);
            
            // Update hidden inputs with new order
            quizOrder.forEach((quizId, index) => {
                const input = document.getElementById('quiz_order_' + index);
                if (input) {
                    input.value = quizId;
                }
            });
            
            // Submit the form via fetch for AJAX update
            const formData = new FormData(document.getElementById('reorderQuizzesForm'));
            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                showSuccessMessage('Quiz order saved!');
            }).catch(error => {
                console.error('Error saving quiz order:', error);
            });
        }
        
        // =====================
        // Lesson Reordering
        // =====================

        
        // =====================
        // Question Reordering
        // =====================
        function saveQuestionOrder(quizId) {
            const draggableList = document.getElementById('questionsList-' + quizId);
            if (!draggableList) return;
            
            const items = [...draggableList.querySelectorAll('.draggable-item')];
            const questionOrder = items.map(item => item.dataset.questionId);
            
            // Update hidden inputs with new order
            questionOrder.forEach((questionId, index) => {
                const input = document.getElementById('question_order_' + quizId + '_' + index);
                if (input) {
                    input.value = questionId;
                }
            });
            
            // Submit the form via fetch for AJAX update
            const formData = new FormData(document.getElementById('reorderQuestionsForm-' + quizId));
            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                showSuccessMessage('Question order saved!');
            }).catch(error => {
                console.error('Error saving question order:', error);
            });
        }
        
        // =====================
        // Utility Functions
        // =====================
        function showSuccessMessage(message) {
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success';
            successMsg.textContent = message;
            successMsg.style.position = 'fixed';
            successMsg.style.top = '20px';
            successMsg.style.right = '20px';
            successMsg.style.zIndex = '9999';
            document.body.appendChild(successMsg);
            
            setTimeout(() => {
                successMsg.remove();
            }, 2000);
        }
        
          // Initialize all drag events and load expanded modules
          document.addEventListener('DOMContentLoaded', function() {
              initDragEvents('modulesList', 'modules');
              initDragEvents('quizzesList', 'quizzes');
              
              // Initialize question list drag events for all quizzes
              <?php foreach ($quizzes as $quiz): ?>
              initDragEvents('questionsList-<?= $quiz['id'] ?>', 'questions');
              <?php endforeach; ?>
              
              // Initialize lesson list drag events for all modules
              <?php foreach ($modules as $module): ?>
              initDragEvents('lessonsList-<?= $module['id'] ?>', 'lessons');
              <?php endforeach; ?>
              
              // Load saved expanded modules
              loadExpandedModules();
          });
    </script>
    <!-- Quill Rich Text Editor Script -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        // Initialize Quill editors
        let quillEditors = {};
        
        function initQuillEditor(elementId) {
            const container = document.getElementById(elementId);
            if (container && !quillEditors[elementId]) {
                quillEditors[elementId] = new Quill('#' + elementId, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Type something...'
                });
            }
        }
        
         // Modify existing openModal function to initialize Quill editors
         // We'll replace the original function with the one that includes Quill initialization
          function openModal(type, id = null) {
              if (type === 'addModule') {
                  document.getElementById('addModuleModal').classList.add('active');
                  initQuillEditor('addModuleDescription');
              } else if (type === 'editModule') {
                  const module = modulesData.find(m => m.id === id);
                  if (module) {
                      document.getElementById('editModuleId').value = module.id;
                      document.getElementById('editModuleTitle').value = module.title;
                      initQuillEditor('editModuleDescription');
                      // Set existing content - don't unescape
                      quillEditors['editModuleDescription'].root.innerHTML = module.description || '';
                      document.getElementById('editModulePublished').checked = module.is_published == 1;
                      document.getElementById('editModuleModal').classList.add('active');
                  }
              } else if (type === 'deleteModule') {
                  document.getElementById('deleteModuleId').value = id;
                  document.getElementById('deleteModuleModal').classList.add('active');
              } else if (type === 'addLesson') {
                  document.getElementById('addLessonModuleId').value = id;
                  document.getElementById('addLessonModal').classList.add('active');
                  initQuillEditor('addLessonDescription');
                  initQuillEditor('addLessonContent');
               } else if (type === 'editLesson') {
                   const lesson = Object.values(lessonsData).flat().find(l => l.id === id);
                   if (lesson) {
                       document.getElementById('editLessonId').value = lesson.id;
                       document.getElementById('editLessonTitle').value = lesson.title;
                       initQuillEditor('editLessonDescription');
                       initQuillEditor('editLessonContent');
                       // Set existing content - don't unescape, Quill handles it
                       quillEditors['editLessonDescription'].root.innerHTML = lesson.description || '';
                       quillEditors['editLessonContent'].root.innerHTML = lesson.content || '';
                        document.getElementById('editLessonVideoUrl').value = unescapeHtml(lesson.video_url || '');
                        document.getElementById('editLessonVideoStart').value = lesson.video_start || '';
                        document.getElementById('editLessonVideoStop').value = lesson.video_stop || '';
                        document.getElementById('editLessonVideoDuration').value = lesson.video_duration || '';
                       document.getElementById('editLessonIsFree').checked = lesson.is_free == 1;
                       document.getElementById('editLessonIsPublished').checked = lesson.is_published == 1;
                       
                       // Initialize video toggle
                       const hasVideo = lesson.video_url && lesson.video_url.trim() !== '';
                       document.getElementById('editLessonHasVideo').checked = hasVideo;
                       document.getElementById('editLessonVideoFields').style.display = hasVideo ? 'block' : 'none';
                      
                      // Debug: Set and log lesson ID for resources
                      const resourceLessonIdInput = document.getElementById('resourceLessonId');
                      if (resourceLessonIdInput) {
                          resourceLessonIdInput.value = lesson.id;
                          console.log('Resource Lesson ID set to:', lesson.id);
                      } else {
                          console.error('resourceLessonId input not found');
                      }
                      
                      // Load resources
                      loadLessonResources(lesson.id);
                      document.getElementById('editLessonModal').classList.add('active');
                  } else {
                      console.error('Lesson not found with ID:', id);
                  }
              } else if (type === 'addQuiz') {
                  document.getElementById('addQuizModal').classList.add('active');
                  initQuillEditor('addQuizDescription');
              } else if (type === 'editQuiz') {
                  const quiz = quizzesData[id];
                  if (quiz) {
                      document.getElementById('editQuizId').value = quiz.id;
                      document.getElementById('editQuizTitle').value = quiz.title;
                      initQuillEditor('editQuizDescription');
                      // Set existing content - don't unescape
                      quillEditors['editQuizDescription'].root.innerHTML = quiz.description || '';
                      document.getElementById('editQuizTimeLimit').value = quiz.time_limit || 0;
                      document.getElementById('editQuizPassingScore').value = quiz.passing_score;
                      document.getElementById('editQuizMaxAttempts').value = quiz.max_attempts;
                      document.getElementById('editQuizIsFinal').checked = quiz.is_final == 1;
                      document.getElementById('editQuizPublished').checked = quiz.published == 1;
                      document.getElementById('editQuizModal').classList.add('active');
                  }
              } else if (type === 'addQuestion') {
                  document.getElementById('addQuestionQuizId').value = id;
                  document.getElementById('addQuestionModal').classList.add('active');
                  initQuillEditor('addQuestionText');
                  initQuillEditor('addQuestionExplanation');
              } else if (type === 'deleteQuiz') {
                  document.getElementById('deleteQuizId').value = id;
                  document.getElementById('deleteQuizModal').classList.add('active');
              }
          }
          
          // Helper function to unescape HTML entities
          function unescapeHtml(text) {
              if (!text) return '';
              const textarea = document.createElement('textarea');
              textarea.innerHTML = text;
              return textarea.value;
          }
        
        // Save Quill content to hidden inputs before form submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                // Save module description
                if (quillEditors['addModuleDescription']) {
                    document.getElementById('addModuleDescriptionHidden').value = quillEditors['addModuleDescription'].root.innerHTML;
                }
                if (quillEditors['editModuleDescription']) {
                    document.getElementById('editModuleDescriptionHidden').value = quillEditors['editModuleDescription'].root.innerHTML;
                }
                
                // Save lesson description and content
                if (quillEditors['addLessonDescription']) {
                    document.getElementById('addLessonDescriptionHidden').value = quillEditors['addLessonDescription'].root.innerHTML;
                }
                if (quillEditors['addLessonContent']) {
                    document.getElementById('addLessonContentHidden').value = quillEditors['addLessonContent'].root.innerHTML;
                }
                if (quillEditors['editLessonDescription']) {
                    document.getElementById('editLessonDescriptionHidden').value = quillEditors['editLessonDescription'].root.innerHTML;
                }
                if (quillEditors['editLessonContent']) {
                    document.getElementById('editLessonContentHidden').value = quillEditors['editLessonContent'].root.innerHTML;
                }
                
                // Save quiz description
                if (quillEditors['addQuizDescription']) {
                    document.getElementById('addQuizDescriptionHidden').value = quillEditors['addQuizDescription'].root.innerHTML;
                }
                if (quillEditors['editQuizDescription']) {
                    document.getElementById('editQuizDescriptionHidden').value = quillEditors['editQuizDescription'].root.innerHTML;
                }
                
                // Save question text and explanation
                if (quillEditors['addQuestionText']) {
                    document.getElementById('addQuestionTextHidden').value = quillEditors['addQuestionText'].root.innerHTML;
                }
                if (quillEditors['addQuestionExplanation']) {
                    document.getElementById('addQuestionExplanationHidden').value = quillEditors['addQuestionExplanation'].root.innerHTML;
                }
            });
        });
    </script>
</body>
</html>
