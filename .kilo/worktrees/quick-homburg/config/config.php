<?php
/**
 * Data Tutors - Configuration File
 * Database and System Configuration
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
session_start([
    'cookie_lifetime' => 86400, // 24 hours
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true
]);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'data_tutors');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'Data Tutors');
define('APP_URL', 'https://data_tutors.test');
define('APP_EMAIL', 'info@datatutors.com');
define('APP_TIMEZONE', 'Africa/Lagos');

// PWA Configuration
define('PWA_NAME', 'Data Tutors');
define('PWA_SHORT_NAME', 'DataTutors');
define('PWA_THEME_COLOR', '#2563eb');
define('PWA_BG_COLOR', '#ffffff');
define('PWA_ICON_192', APP_URL . '/assets/images/icon-192.png');
define('PWA_ICON_512', APP_URL . '/assets/images/icon-512.png');

// Payment Configuration (Paystack)
define('PAYSTACK_PUBLIC_KEY', 'pk_test_your_public_key');
define('PAYSTACK_SECRET_KEY', 'sk_test_your_secret_key');
define('PAYSTACK_CALLBACK_URL', APP_URL . '/payments/callback.php');

// Email Configuration
define('SMTP_HOST', 'smtp.mailtrap.io');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_smtp_user');
define('SMTP_PASS', 'your_smtp_password');

// File Upload Configuration
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Date format
define('DATE_FORMAT', 'Y-m-d H:i:s');

// Database Connection Class
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
    
    public static function query($sql, $params = []) {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

// Helper Functions
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Permission constants
define('ROLE_SUPER_ADMIN', 'super_admin');
define('ROLE_ADMIN', 'admin');
define('ROLE_TUTOR', 'tutor');
define('ROLE_INSTRUCTOR', 'instructor');
define('ROLE_STUDENT', 'student');

// Permission levels (higher number = more permissions)
$permissionLevels = [
    ROLE_STUDENT => 1,
    ROLE_TUTOR => 2,
    ROLE_INSTRUCTOR => 3,
    ROLE_ADMIN => 4,
    ROLE_SUPER_ADMIN => 5
];

// ============================================
// USER SESSION FUNCTIONS
// ============================================

/**
 * Check if user is logged in (regular user)
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user (regular user)
 */
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'username' => $_SESSION['user_username'] ?? '',
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    return null;
}

/**
 * Login user (regular user)
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_username'] = $user['username'] ?? '';
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['logged_in_at'] = time();
}

/**
 * Logout user (regular user)
 */
function logoutUser() {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);
    unset($_SESSION['logged_in_at']);
}

// ============================================
// ADMIN SESSION FUNCTIONS (Separate from users)
// ============================================

/**
 * Check if admin is logged in (separate from user session)
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Check if user is any type of admin (super_admin, admin, or tutor)
 */
function isAdmin() {
    if (!isAdminLoggedIn()) return false;
    $role = $_SESSION['admin_role'] ?? '';
    return in_array($role, [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_TUTOR]);
}

/**
 * Check if user is super admin (full access)
 */
function isSuperAdmin() {
    if (!isAdminLoggedIn()) return false;
    return ($_SESSION['admin_role'] ?? '') === ROLE_SUPER_ADMIN;
}

/**
 * Check if user is admin (management access)
 */
function isAdminUser() {
    if (!isAdminLoggedIn()) return false;
    $role = $_SESSION['admin_role'] ?? '';
    return in_array($role, [ROLE_SUPER_ADMIN, ROLE_ADMIN]);
}

/**
 * Check if user is tutor
 */
function isTutor() {
    if (!isAdminLoggedIn()) return false;
    return ($_SESSION['admin_role'] ?? '') === ROLE_TUTOR;
}

/**
 * Get current admin user
 */
function getCurrentAdmin() {
    if (isAdminLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email'],
            'role' => $_SESSION['admin_role']
        ];
    }
    return null;
}

/**
 * Login admin user (separate session from regular users)
 */
function loginAdmin($user) {
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_name'] = $user['name'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_role'] = $user['role'];
    $_SESSION['admin_logged_in_at'] = time();
}

/**
 * Logout admin user
 */
function logoutAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_role']);
    unset($_SESSION['admin_logged_in_at']);
}

/**
 * Logout both user and admin
 */
function logoutAll() {
    logoutUser();
    logoutAdmin();
    session_destroy();
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function formatDate($date) {
    if (empty($date)) {
        return 'N/A';
    }
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return 'N/A';
    }
    return date('F j, Y', $timestamp);
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    $units = [
        31536000 => 'year',
        2592000 => 'month',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    ];
    
    foreach ($units as $value => $unit) {
        if ($time >= $value) {
            $count = floor($time / $value);
            return $count . ' ' . $unit . ($count > 1 ? 's' : '') . ' ago';
        }
    }
    return 'just now';
}

function logActivity($userId, $action, $entityType = null, $entityId = null, $oldData = null, $newData = null) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, entity_type, entity_id, old_data, new_data, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$userId, $action, $entityType, $entityId, $oldData ? json_encode($oldData) : null, $newData ? json_encode($newData) : null, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
}
