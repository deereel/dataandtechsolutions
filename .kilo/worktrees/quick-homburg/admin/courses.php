<?php
/**
 * Data Tutors - Admin Courses Management
 * Manage courses in the admin panel
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Authentication check - admins and tutors can access
if (!isAdminLoggedIn() || !isAdmin()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(APP_URL . '/admin/login.php');
}

// Get current admin user
$currentAdmin = getCurrentAdmin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdminUser()) {
    $action = $_POST['action'] ?? '';
    
    // Add new course
    if ($action === 'add') {
        $title = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $category = sanitize($_POST['category'] ?? '');
        $level = sanitize($_POST['level'] ?? 'beginner');
        $price = floatval($_POST['price'] ?? 0);
        $duration = intval($_POST['duration'] ?? 0);
        $published = isset($_POST['published']) ? 1 : 0;
        
        if (empty($title) || empty($description) || empty($category)) {
            $error = 'Please fill in all required fields.';
        } else {
            $courseId = Course::create([
                'title' => $title,
                'description' => $description,
                'category' => $category,
                'level' => $level,
                'price' => $price,
                'duration' => $duration,
                'published' => $published,
                'created_by' => $currentAdmin['id']
            ]);
            
            if ($courseId) {
                logActivity($_SESSION['admin_id'], 'create_course', 'courses', $courseId, null, [
                    'title' => $title,
                    'category' => $category
                ]);
                $success = 'Course created successfully.';
            } else {
                $error = 'Failed to create course.';
            }
        }
    }
    
    // Update course
    elseif ($action === 'update') {
        $courseId = intval($_POST['course_id'] ?? 0);
        
        if ($courseId <= 0) {
            $error = 'Invalid course ID.';
        } else {
            $oldCourse = Course::getById($courseId);
            
            $updateData = [
                'title' => sanitize($_POST['title'] ?? ''),
                'description' => sanitize($_POST['description'] ?? ''),
                'category' => sanitize($_POST['category'] ?? ''),
                'level' => sanitize($_POST['level'] ?? 'beginner'),
                'price' => floatval($_POST['price'] ?? 0),
                'duration' => intval($_POST['duration'] ?? 0),
                'published' => isset($_POST['published']) ? 1 : 0
            ];
            
            Course::update($courseId, $updateData);
            logActivity($_SESSION['admin_id'], 'update_course', 'courses', $courseId, $oldCourse, $updateData);
            $success = 'Course updated successfully.';
        }
    }
    
    // Delete course
    elseif ($action === 'delete') {
        $courseId = intval($_POST['course_id'] ?? 0);
        
        if ($courseId <= 0) {
            $error = 'Invalid course ID.';
        } else {
            $course = Course::getById($courseId);
            if ($course) {
                Course::delete($courseId);
                logActivity($_SESSION['admin_id'], 'delete_course', 'courses', $courseId, $course, null);
                $success = 'Course deleted successfully.';
            } else {
                $error = 'Course not found.';
            }
        }
    }
}

// Get all courses
$courses = Course::getAll();

// Get course statistics
$stats = [
    'total' => count($courses),
    'published' => count(array_filter($courses, fn($c) => $c['published'])),
    'drafts' => count(array_filter($courses, fn($c) => !$c['published'])),
    'total_enrollments' => DatabaseConnection::fetchColumn("SELECT COUNT(*) FROM enrollments")
];

define('PAGE_TITLE', 'Course Management');
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
        .page-header h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
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
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        .btn-sm {
            padding: 0.5rem 0.875rem;
            font-size: 0.8125rem;
        }
        .admin-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .stat-label {
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-100);
        }
        th {
            background: var(--gray-50);
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        tr:hover {
            background: var(--gray-50);
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .badge-info { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
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
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 600px;
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
        .modal-header h2 {
            font-size: 1.25rem;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-400);
        }
        .modal-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .checkbox-label input {
            width: auto;
        }
        .course-info {
            display: flex;
            flex-direction: column;
        }
        .course-title {
            font-weight: 500;
            color: var(--gray-900);
        }
        .course-meta {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        .actions {
            display: flex;
            gap: 0.5rem;
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
                    <h1>Course Management</h1>
                    <p style="color: var(--gray-500);">Manage courses and curriculum</p>
                </div>
                <?php if (isAdminUser()): ?>
                <button class="btn btn-primary" onclick="openModal('add')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add New Course
                </button>
                <?php endif; ?>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= sanitize($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= sanitize($success) ?></div>
            <?php endif; ?>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total'] ?></div>
                    <div class="stat-label">Total Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['published'] ?></div>
                    <div class="stat-label">Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['drafts'] ?></div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_enrollments'] ?></div>
                    <div class="stat-label">Total Enrollments</div>
                </div>
            </div>
            
            <!-- Courses Table -->
            <div class="admin-card">
                <div class="card-header">
                    <h3 class="card-title">All Courses</h3>
                    <span style="color: var(--gray-500); font-size: 0.875rem;">
                        <?= count($courses) ?> course<?= count($courses) !== 1 ? 's' : '' ?> found
                    </span>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Enrollments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                            <?php
                            $enrollments = DatabaseConnection::fetchColumn(
                                "SELECT COUNT(*) FROM enrollments WHERE course_id = ?",
                                [$course['id']]
                            );
                            ?>
                            <tr>
                                <td>
                                    <div class="course-info">
                                        <span class="course-title"><?= sanitize($course['title']) ?></span>
                                        <span class="course-meta"><?= $course['duration'] ?? 0 ?> mins</span>
                                    </div>
                                </td>
                                <td><?= ucfirst(str_replace('-', ' ', $course['category'])) ?></td>
                                <td><?= ucfirst($course['level']) ?></td>
                                <td>$<?= number_format($course['price'], 2) ?></td>
                                <td>
                                    <span class="badge <?= $course['published'] ? 'badge-success' : 'badge-warning' ?>">
                                        <?= $course['published'] ? 'Published' : 'Draft' ?>
                                    </span>
                                </td>
                                <td><?= $enrollments ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?= APP_URL ?>/admin/course-manage.php?course_id=<?= $course['id'] ?>" class="btn btn-primary btn-sm">
                                            Manage
                                        </a>
                                        <button class="btn btn-secondary btn-sm" onclick="openModal('edit', <?= $course['id'] ?>)">
                                            Edit
                                        </button>
                                        <?php if (isSuperAdmin()): ?>
                                        <button class="btn btn-danger btn-sm" onclick="deleteCourse(<?= $course['id'] ?>)">
                                            Delete
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add/Edit Modal -->
    <div class="modal" id="courseModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Course</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form method="POST" id="courseForm">
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="course_id" id="courseId" value="">
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Course Title *</label>
                        <input type="text" id="title" name="title" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" class="form-textarea" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="excel">Excel</option>
                            <option value="data-analysis">Data Analysis</option>
                            <option value="automation">Automation</option>
                            <option value="power-bi">Power BI</option>
                            <option value="sql">SQL</option>
                            <option value="python">Python</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="level" class="form-label">Level</label>
                        <select id="level" name="level" class="form-select">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" id="price" name="price" class="form-input" step="0.01" min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="duration" class="form-label">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" class="form-input" min="0" value="60">
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="published" id="published" checked>
                            <span>Publish immediately</span>
                        </label>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <form method="POST" id="deleteForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="course_id" id="deleteCourseId" value="">
                    <p>Are you sure you want to delete this course? This action cannot be undone and will also delete all associated lessons and enrollments.</p>
                </div>
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Course</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        const coursesData = <?= json_encode(array_combine(array_column($courses, 'id'), $courses)) ?>;
        
        function openModal(action, courseId = null) {
            const modal = document.getElementById('courseModal');
            const form = document.getElementById('courseForm');
            const title = document.getElementById('modalTitle');
            
            form.reset();
            document.getElementById('formAction').value = action;
            document.getElementById('courseId').value = '';
            
            if (action === 'add') {
                title.textContent = 'Add New Course';
                document.getElementById('published').checked = true;
            } else {
                title.textContent = 'Edit Course';
                const course = coursesData[courseId];
                if (course) {
                    document.getElementById('courseId').value = course.id;
                    document.getElementById('title').value = course.title;
                    document.getElementById('description').value = course.description;
                    document.getElementById('category').value = course.category;
                    document.getElementById('level').value = course.level;
                    document.getElementById('price').value = course.price;
                    document.getElementById('duration').value = course.duration;
                    document.getElementById('published').checked = course.published == 1;
                }
            }
            
            modal.classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('courseModal').classList.remove('active');
        }
        
        function deleteCourse(courseId) {
            document.getElementById('deleteCourseId').value = courseId;
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        // Close modals on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
