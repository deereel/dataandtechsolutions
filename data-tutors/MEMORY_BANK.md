# Data Tutors - Memory Bank

## Project Overview
Data Tutors is a PHP-based online learning management system with both user-facing and admin panels.

## Tech Stack
- **Backend**: PHP with PDO
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **PWA**: Progressive Web App support

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `password` - Hashed password
- `role` - 'student', 'super_admin', 'admin', 'tutor'
- `status` - 'active', 'inactive', 'blocked'
- `phone`, `bio`, `avatar` - Optional profile fields
- `email_verified`, `last_login`, `created_at`

### Courses Table
- `id` - Primary key
- `title`, `description`
- `category` - 'excel', 'data-analysis', 'automation', etc.
- `level` - 'beginner', 'intermediate', 'advanced'
- `price`, `duration`
- `published` - Boolean
- `created_by` - Admin user ID

### Other Tables
- `enrollments` - User-course relationships
- `forum_questions` - Forum posts
- `forum_answers` - Forum responses
- `quiz_results` - Quiz attempts
- `certificates` - Course completion certificates
- `activity_log` - Admin action tracking

## Authentication System

### User Sessions
- `$_SESSION['user_id']` - Regular user ID
- `$_SESSION['user_name']`, `$_SESSION['user_email']`, `$_SESSION['user_role']`

### Admin Sessions (Separate)
- `$_SESSION['admin_id']` - Admin user ID
- `$_SESSION['admin_name']`, `$_SESSION['admin_email']`, `$_SESSION['admin_role']`
- `$_SESSION['admin_logged_in_at']`

### Helper Functions
- `isLoggedIn()` - Check if user is logged in
- `isAdminLoggedIn()` - Check if admin is logged in
- `isAdmin()` - Check if logged in user is any admin (super_admin, admin, tutor)
- `isSuperAdmin()` - Check if super admin
- `isAdminUser()` - Check if super_admin or admin
- `loginUser()`, `logoutUser()` - User session management
- `loginAdmin()`, `logoutAdmin()` - Admin session management

## Role Hierarchy
1. **super_admin** - Full access to all features
2. **admin** - Management access (cannot delete super admins)
3. **tutor** - Course and content management
4. **student** - Regular user

## Admin Panel Pages
- `/admin/index.php` - Dashboard with stats
- `/admin/users.php` - Tabbed user management (Students + Admin tabs)
- `/admin/courses.php` - Course management
- `/admin/forum.php` - Forum moderation
- `/admin/settings.php` - Admin settings (super admin only)
- `/admin/login.php` - Admin login
- `/admin/logout.php` - Admin logout

## Key Files
- `config/config.php` - Configuration and session functions
- `config/database.php` - Database helper classes
- `auth/login.php` - User login
- `auth/register.php` - User registration
- `dashboard/profile.php` - User profile page
- `dashboard/index.php` - User dashboard

## Important Notes
1. Admin and user sessions are completely separate
2. Only super admins can perform CRUD on Users and Admin tabs
3. Regular users cannot access admin panel
4. Profile page uses `DatabaseConnection::` class methods
5. Admin sidebar shows logged-in admin user info
6. PWA manifest and service worker for offline support

## Recent Changes
- Consolidated Users and Admin Users into single tabbed page
- Added logged-in admin user display in sidebar
- Removed admin link from user header
- Fixed profile page null checks
- Created all admin pages (courses, forum, settings)
- Added `order_index` column to quizzes table for sorting
- Implemented quiz reordering functionality in admin panel
- Fixed quiz order to ensure sequential order_index values
- Updated quiz structure to match module/lesson accordion pattern
