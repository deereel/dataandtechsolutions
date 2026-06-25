<?php
/**
 * Data Tutors - Courses Listing Page
 * Browse and search all available courses
 */

require_once '../config/config.php';
require_once '../config/database.php';

// Get filters from URL
$category = sanitize($_GET['category'] ?? '');
$level = sanitize($_GET['level'] ?? '');
$search = sanitize($_GET['search'] ?? '');
$sort = sanitize($_GET['sort'] ?? 'newest');
$page = max(1, intval($_GET['page'] ?? 1));
$coursesPerPage = 9;

// Build query
$sql = "SELECT * FROM courses WHERE published = 1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = :category";
    $params['category'] = $category;
}

if (!empty($level)) {
    $sql .= " AND level = :level";
    $params['level'] = $level;
}

if (!empty($search)) {
    $sql .= " AND (title LIKE :search OR description LIKE :search)";
    $params['search'] = "%{$search}%";
}

// Sorting
switch ($sort) {
    case 'oldest':
        $sql .= " ORDER BY created_at ASC";
        break;
    case 'popular':
        $sql .= " ORDER BY (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = courses.id) DESC";
        break;
    case 'price-low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-high':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY created_at DESC";
}

// Get total count
$countSql = str_replace('*', 'COUNT(*) as total', explode('ORDER BY', $sql)[0]);
$totalCourses = DatabaseConnection::fetchColumn($countSql, $params);
$totalPages = ceil($totalCourses / $coursesPerPage);

// Add pagination
$offset = ($page - 1) * $coursesPerPage;
$sql .= " LIMIT {$coursesPerPage} OFFSET {$offset}";

// Fetch courses
$courses = DatabaseConnection::fetchAll($sql, $params);

// Categories for filter
$categories = [
    '' => 'All Categories',
    'excel' => 'Excel',
    'data-analysis' => 'Data Analysis',
    'automation' => 'Data Automation'
];

$levels = [
    '' => 'All Levels',
    'beginner' => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced' => 'Advanced'
];

$sortOptions = [
    'newest' => 'Newest First',
    'oldest' => 'Oldest First',
    'popular' => 'Most Popular',
    'price-low' => 'Price: Low to High',
    'price-high' => 'Price: High to Low'
];

define('PAGE_TITLE', 'Browse Courses');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .courses-page {
            padding-top: 100px;
            min-height: 100vh;
            background: var(--gray-50);
        }
        .courses-header {
            background: var(--white);
            padding: 2rem 0;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 2rem;
        }
        .courses-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .courses-header p {
            color: var(--gray-600);
        }
        .courses-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            padding-bottom: 4rem;
        }
        .filters-sidebar {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            height: fit-content;
            position: sticky;
            top: 90px;
        }
        .filter-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .filter-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .filter-label {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
            display: block;
        }
        .filter-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
        }
        .filter-option:hover {
            color: var(--primary);
        }
        .filter-option input {
            accent-color: var(--primary);
        }
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 1rem;
        }
        .search-box svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .course-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }
        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .course-image {
            height: 160px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .course-image.excel {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }
        .course-image.data-analysis {
            background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
        }
        .course-image.automation {
            background: linear-gradient(135deg, #ea580c 0%, #f59e0b 100%);
        }
        .course-info {
            padding: 1.25rem;
        }
        .course-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        .course-title {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
        }
        .course-description {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .course-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }
        .course-meta span {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .course-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-100);
        }
        .course-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .course-price .free {
            color: var(--success);
        }
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--gray-600);
            transition: var(--transition);
        }
        .pagination a:hover {
            background: var(--gray-100);
        }
        .pagination .active {
            background: var(--primary);
            color: white;
        }
        .pagination .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .results-count {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
        }
        .sort-select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
        }
        @media (max-width: 1024px) {
            .courses-layout {
                grid-template-columns: 1fr;
            }
            .filters-sidebar {
                position: static;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="courses-page">
        <div class="courses-header">
            <div class="container">
                <h1>Browse Courses</h1>
                <p>Discover courses to advance your data skills</p>
            </div>
        </div>
        
        <div class="container">
            <div class="courses-layout">
                <!-- Filters Sidebar -->
                <aside class="filters-sidebar">
                    <form method="GET" action="">
                        <div class="search-box">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"></path></svg>
                            <input type="text" name="search" placeholder="Search courses..." value="<?= sanitize($search) ?>">
                        </div>
                        
                        <div class="filter-section">
                            <label class="filter-label">Category</label>
                            <?php foreach ($categories as $value => $label): ?>
                                <label class="filter-option">
                                    <input type="radio" name="category" value="<?= $value ?>" <?= $category === $value ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="filter-section">
                            <label class="filter-label">Level</label>
                            <?php foreach ($levels as $value => $label): ?>
                                <label class="filter-option">
                                    <input type="radio" name="level" value="<?= $value ?>" <?= $level === $value ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <?= $label ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="sort" value="<?= sanitize($sort) ?>">
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" class="btn btn-primary btn-sm" style="flex: 1;">Apply Filters</button>
                            <a href="/course/index.php" class="btn btn-secondary btn-sm">Clear</a>
                        </div>
                    </form>
                </aside>
                
                <!-- Courses Grid -->
                <div class="courses-content">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <p class="results-count"><?= $totalCourses ?> courses found</p>
                        <form method="GET" action="">
                            <?php if ($category): ?>
                                <input type="hidden" name="category" value="<?= sanitize($category) ?>">
                            <?php endif; ?>
                            <?php if ($level): ?>
                                <input type="hidden" name="level" value="<?= sanitize($level) ?>">
                            <?php endif; ?>
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?= sanitize($search) ?>">
                            <?php endif; ?>
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <?php foreach ($sortOptions as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $sort === $value ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="courses-grid">
                        <?php if (empty($courses)): ?>
                            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--white); border-radius: var(--radius-lg);">
                                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem; color: var(--gray-400);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 style="color: var(--gray-600); margin-bottom: 0.5rem;">No courses found</h3>
                                <p style="color: var(--gray-500);">Try adjusting your filters or search terms</p>
                                <a href="/course/index.php" class="btn btn-primary" style="margin-top: 1rem;">Clear Filters</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                                <div class="course-card">
                                    <div class="course-image <?= $course['category'] ?>">
                                        <?php if ($course['category'] === 'excel'): ?>
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="white"><path d="M3 3h18v18H3V3zm16 16V5H5v14h14zM7 7h4v2H7V7zm0 4h4v2H7v-2zm0 4h2v2H7v-2zm6-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2zm-4 4h4v2H9v-2zm0-8h4v2H9V7z"/></svg>
                                        <?php elseif ($course['category'] === 'data-analysis'): ?>
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="white"><path d="M3 3h18v18H3V3zm4 14h2V7H7v10zm4-4h2V7h-2v6z"/></svg>
                                        <?php else: ?>
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="white"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="course-info">
                                        <span class="course-badge"><?= ucfirst($course['level']) ?></span>
                                        <h3 class="course-title"><?= sanitize($course['title']) ?></h3>
                                        <p class="course-description"><?= sanitize($course['short_description'] ?? substr($course['description'], 0, 100) . '...') ?></p>
                                        <div class="course-meta">
                                            <span>
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <?= $course['duration_hours'] ?: 'N/A' ?>h
                                            </span>
                                            <span>
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <?= ucfirst($course['category']) ?>
                                            </span>
                                        </div>
                                        <div class="course-footer">
                                            <span class="course-price <?= $course['price'] == 0 ? 'free' : '' ?>">
                                                <?= $course['price'] == 0 ? 'Free' : '$' . number_format($course['price'], 2) ?>
                                            </span>
                                            <a href="details.php?id=<?= $course['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a>
                            <?php else: ?>
                                <span class="disabled">Previous</span>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
                            <?php else: ?>
                                <span class="disabled">Next</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
