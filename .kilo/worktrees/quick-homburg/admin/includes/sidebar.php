<?php
/**
 * Admin Sidebar
 * Shared sidebar for all admin pages
 */

// Get current admin user
$currentAdmin = getCurrentAdmin();

// Role badge colors
$roleColors = [
    'super_admin' => 'badge-danger',
    'admin' => 'badge-warning',
    'tutor' => 'badge-info',
    'instructor' => 'badge-info',
    'student' => 'badge-success'
];
?>

<div class="admin-logo">
    <img src="<?= APP_URL ?>/assets/images/logo.png" alt="Data Tutors" style="height: 40px;">
</div>

<!-- Logged In Admin User -->
<div style="background: var(--gray-800); border-radius: var(--radius); padding: 1rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; color: white;">
            <?= strtoupper(substr($currentAdmin['name'], 0, 2)) ?>
        </div>
        <div>
            <div style="font-weight: 500; font-size: 0.875rem;"><?= sanitize($currentAdmin['name']) ?></div>
            <div style="font-size: 0.75rem; color: var(--gray-400);"><?= sanitize($currentAdmin['email']) ?></div>
        </div>
    </div>
    <div style="text-align: center;">
        <span class="badge <?= $roleColors[$currentAdmin['role']] ?? 'badge-info' ?>" style="font-size: 0.7rem;">
            <?= ucfirst(str_replace('_', ' ', $currentAdmin['role'])) ?>
        </span>
    </div>
</div>

<nav>
    <ul class="admin-nav">
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/index.php" class="admin-nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/courses.php" class="admin-nav-link <?= basename($_SERVER['PHP_SELF']) === 'courses.php' || basename($_SERVER['PHP_SELF']) === 'course-manage.php' ? 'active' : '' ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Courses
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/users.php" class="admin-nav-link <?= basename($_SERVER['PHP_SELF']) === 'users.php' || basename($_SERVER['PHP_SELF']) === 'student-detail.php' ? 'active' : '' ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/forum.php" class="admin-nav-link <?= basename($_SERVER['PHP_SELF']) === 'forum.php' ? 'active' : '' ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Forum Moderation
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/settings.php" class="admin-nav-link <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>" class="admin-nav-link">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                View Site
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="<?= APP_URL ?>/admin/logout.php" class="admin-nav-link">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
        </li>
    </ul>
</nav>
