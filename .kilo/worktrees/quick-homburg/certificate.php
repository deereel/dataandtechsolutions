<?php
/**
 * Data Tutors - Certificate Viewer
 * View and download course completion certificates
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Get certificate ID or enrollment ID
$certificateId = intval($_GET['id'] ?? 0);
$enrollmentId = intval($_GET['enrollment'] ?? 0);

if (!$certificateId && !$enrollmentId) {
    redirect(APP_URL . '/dashboard/index.php');
}

// Fetch certificate
if ($certificateId) {
    $certificate = DatabaseConnection::fetchOne(
        "SELECT c.*, u.name as user_name, u.email, cr.title as course_title, cr.category, cr.instructor 
         FROM certificates c 
         JOIN users u ON c.user_id = u.id 
         JOIN courses cr ON c.course_id = cr.id 
         WHERE c.id = ?",
        [$certificateId]
    );
} else {
    $certificate = DatabaseConnection::fetchOne(
        "SELECT c.*, u.name as user_name, u.email, cr.title as course_title, cr.category, cr.instructor 
         FROM certificates c 
         JOIN users u ON c.user_id = u.id 
         JOIN courses cr ON c.course_id = cr.id 
         WHERE c.enrollment_id = ?",
        [$enrollmentId]
    );
}

if (!$certificate) {
    redirect(APP_URL . '/dashboard/index.php');
}

// Verify ownership or admin access
$userId = isLoggedIn() ? $_SESSION['user_id'] : 0;
if ($certificate['user_id'] != $userId && !isAdmin()) {
    redirect(APP_URL . '/dashboard/index.php');
}

define('PAGE_TITLE', 'Certificate of Completion');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= PAGE_TITLE ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .certificate-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .certificate-container {
            max-width: 1000px;
            width: 100%;
        }
        .certificate {
            background: white;
            border: 8px solid var(--primary);
            border-radius: 8px;
            padding: 3rem;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            bottom: 1rem;
            border: 2px solid var(--primary);
            border-radius: 4px;
            pointer-events: none;
        }
        .certificate-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .certificate-logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .certificate-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-body {
            text-align: center;
            padding: 2rem 0;
        }
        .certificate-presented {
            font-size: 1rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }
        .certificate-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
            font-family: 'Georgia', serif;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            padding-bottom: 0.5rem;
        }
        .certificate-completion {
            font-size: 1rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }
        .certificate-course {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .certificate-category {
            font-size: 0.875rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
        }
        .certificate-signature {
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-bottom: 1px solid var(--gray-300);
            margin-bottom: 0.5rem;
        }
        .signature-name {
            font-weight: 600;
            color: var(--gray-700);
        }
        .signature-title {
            font-size: 0.8rem;
            color: var(--gray-500);
        }
        .certificate-date {
            text-align: right;
        }
        .certificate-id {
            font-size: 0.75rem;
            color: var(--gray-400);
            margin-top: 2rem;
            text-align: center;
        }
        .certificate-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        @media print {
            .certificate-page {
                background: white;
                padding: 0;
            }
            .certificate-actions, .no-print {
                display: none;
            }
            .certificate {
                box-shadow: none;
                border-width: 4px;
            }
        }
        @media (max-width: 768px) {
            .certificate {
                padding: 1.5rem;
            }
            .certificate-name {
                font-size: 1.75rem;
            }
            .certificate-course {
                font-size: 1.25rem;
            }
            .certificate-footer {
                flex-direction: column;
                gap: 2rem;
                align-items: center;
            }
            .certificate-date {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-page">
        <div class="certificate-container">
            <div class="certificate" id="certificate">
                <div class="certificate-header">
                    <div class="certificate-logo"><?= APP_NAME ?></div>
                    <div class="certificate-subtitle">Certificate of Completion</div>
                </div>
                
                <div class="certificate-body">
                    <p class="certificate-presented">This is to certify that</p>
                    <h1 class="certificate-name"><?= sanitize($certificate['user_name']) ?></h1>
                    <p class="certificate-completion">has successfully completed the course</p>
                    <h2 class="certificate-course"><?= sanitize($certificate['course_title']) ?></h2>
                    <p class="certificate-category"><?= ucfirst(str_replace('-', ' ', $certificate['category'])) ?></p>
                </div>
                
                <div class="certificate-footer">
                    <div class="certificate-signature">
                        <div class="signature-line"></div>
                        <div class="signature-name"><?= sanitize($certificate['instructor']) ?></div>
                        <div class="signature-title">Course Instructor</div>
                    </div>
                    
                    <div class="certificate-date">
                        <div class="signature-line"></div>
                        <div class="signature-name"><?= formatDate($certificate['issued_at']) ?></div>
                        <div class="signature-title">Date Issued</div>
                    </div>
                </div>
                
                <div class="certificate-id">
                    Certificate ID: <?= strtoupper(substr($certificate['certificate_id'], 0, 8)) ?>-<?= strtoupper(substr($certificate['certificate_id'], 8, 4)) ?>-<?= strtoupper(substr($certificate['certificate_id'], 12)) ?>
                </div>
            </div>
            
            <div class="certificate-actions no-print">
                <button onclick="window.print()" class="btn btn-primary btn-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Certificate
                </button>
                <a href="/dashboard/index.php" class="btn btn-secondary btn-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
