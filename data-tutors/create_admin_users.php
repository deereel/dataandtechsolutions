<?php
/**
 * Data Tutors - Create Admin Users Script
 * Run this script to create admin users with correct password hashes
 * 
 * Usage: php create_admin_users.php
 */

require_once 'config/config.php';
require_once 'config/database.php';

echo "Creating admin users...\n\n";

// Check if username column exists
try {
    $check = DatabaseConnection::fetch("SHOW COLUMNS FROM users LIKE 'username'");
    if (!$check) {
        echo "Adding username column...\n";
        DatabaseConnection::query("ALTER TABLE users ADD COLUMN username VARCHAR(50) NULL AFTER name");
        DatabaseConnection::query("UPDATE users SET username = SUBSTRING_INDEX(email, '@', 1) WHERE username IS NULL");
        DatabaseConnection::query("ALTER TABLE users MODIFY COLUMN username VARCHAR(50) NOT NULL");
        echo "Username column added.\n\n";
    }
} catch (Exception $e) {
    echo "Error checking/adding username column: " . $e->getMessage() . "\n";
}

// Passwords for the admin users
$adminUsers = [
    [
        'name' => 'Super Admin',
        'username' => 'sadmin',
        'email' => 'sadmin@datatutors.com',
        'password' => 'admin123',
        'role' => 'super_admin',
        'bio' => 'System Super Administrator with full access'
    ],
    [
        'name' => 'Admin',
        'username' => 'admin',
        'email' => 'admin@datatutors.com',
        'password' => 'admin456',
        'role' => 'admin',
        'bio' => 'Administrator with management access'
    ],
    [
        'name' => 'Tutor',
        'username' => 'tutor',
        'email' => 'tutor@datatutors.com',
        'password' => 'admin789',
        'role' => 'tutor',
        'bio' => 'Course Instructor and Tutor'
    ]
];

foreach ($adminUsers as $user) {
    // Check if user already exists
    $existing = DatabaseConnection::fetch("SELECT id FROM users WHERE email = ? OR username = ?", [$user['email'], $user['username']]);
    
    if ($existing) {
        // Update existing user
        echo "Updating user: {$user['email']}\n";
        $updateData = [
            'name' => $user['name'],
            'username' => $user['username'],
            'role' => $user['role'],
            'status' => 'active',
            'email_verified' => 1,
            'bio' => $user['bio'],
            'password' => $user['password']
        ];
        User::update($existing['id'], $updateData);
    } else {
        // Create new user
        echo "Creating user: {$user['email']}\n";
        $userId = User::create([
            'name' => $user['name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'password' => $user['password'],
            'role' => $user['role'],
            'status' => 'active',
            'email_verified' => 1,
            'bio' => $user['bio']
        ]);
        
        if ($userId) {
            echo "  Created with ID: {$userId}\n";
        } else {
            echo "  Failed to create user!\n";
        }
    }
}

// Verify
echo "\nVerifying admin users...\n";
$admins = DatabaseConnection::fetchAll("SELECT id, name, username, email, role, status FROM users WHERE role IN ('super_admin', 'admin', 'tutor')");
foreach ($admins as $admin) {
    echo "- {$admin['name']} (@{$admin['username']}) - {$admin['email']} - {$admin['role']}\n";
}

echo "\nDone! You can now login at https://data_tutors.test/admin/login.php\n";
