<?php
/**
 * admin_auth.php
 * Simplified - Authentication Disabled
 * All pages and files are now accessible without login
 */
session_start();
header('Content-Type: application/json');

// ── LOGOUT ───────────────────────────────────────
if (($_GET['action'] ?? '') === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ./login.php');
    exit;
}

// ── CHECK SESSION ─────────────────────────────────
if (($_GET['action'] ?? '') === 'check') {
    echo json_encode(['logged_in' => true]);
    exit;
}

// ── LOGIN ─────────────────────────────────────────
// Authentication disabled - anyone can login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = $_POST['email'] ?? $_POST['username'] ?? 'admin';
    $_SESSION['admin_since'] = time();
    
    error_log("admin_auth - Login: " . $_SESSION['admin_user']);
    echo json_encode(['status' => 'OK']);
    exit;
}

echo json_encode(['status' => 'ERROR', 'message' => 'Invalid request']);
?>
