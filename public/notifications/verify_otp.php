<?php
/**
 * verify_otp.php
 * Verifies the OTP sent to user's email during appointment booking
 * 
 * Expected POST parameters:
 *   - email: User's email address
 *   - otp: 6-digit OTP code
 * 
 * Returns:
 *   - VERIFIED: OTP is valid and verified
 *   - INVALID: OTP doesn't match
 *   - EXPIRED: OTP has expired (>5 minutes old)
 *   - ALREADY_USED: OTP has already been verified
 *   - MISSING_PARAMS: Required parameters missing
 *   - DB_ERROR: Database connection/query error
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't display errors (breaks JavaScript parsing)
ini_set('log_errors', 1);      // Log errors to PHP error log

header('Content-Type: text/plain');

// ─────────────────────────────────────────────────
// Database Connection
// ─────────────────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "clinic");

if ($conn->connect_error) {
    error_log("verify_otp.php - DB connection failed: " . $conn->connect_error);
    echo "DB_ERROR";
    exit;
}

// ─────────────────────────────────────────────────
// Input Validation
// ─────────────────────────────────────────────────
if (empty($_POST['email']) || empty($_POST['otp'])) {
    error_log("verify_otp.php - Missing required parameters");
    echo "MISSING_PARAMS";
    exit;
}

// Sanitize and validate inputs
$email = trim($_POST['email']);
$otp   = trim($_POST['otp']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("verify_otp.php - Invalid email format: {$email}");
    echo "INVALID_EMAIL";
    exit;
}

// Validate OTP format (must be 6 digits)
if (!preg_match('/^\d{6}$/', $otp)) {
    error_log("verify_otp.php - Invalid OTP format: {$otp}");
    echo "INVALID";
    exit;
}

// Log verification attempt
error_log("verify_otp.php - Verifying email: {$email}, OTP: {$otp}");

// ─────────────────────────────────────────────────
// Check OTP in Database
// ─────────────────────────────────────────────────
$stmt = $conn->prepare(
    "SELECT id, expires_at, verified 
     FROM email_verifications 
     WHERE email = ? AND otp = ?"
);

if (!$stmt) {
    error_log("verify_otp.php - Prepare failed: " . $conn->error);
    echo "DB_ERROR";
    exit;
}

$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

// Check if OTP exists
if ($result->num_rows === 0) {
    error_log("verify_otp.php - No matching OTP found for email: {$email}");
    echo "INVALID";
    $stmt->close();
    $conn->close();
    exit;
}

$row = $result->fetch_assoc();
error_log("verify_otp.php - Found OTP. Expires: {$row['expires_at']}, Verified: {$row['verified']}");

// ─────────────────────────────────────────────────
// Check if OTP Already Used
// ─────────────────────────────────────────────────
if ($row['verified'] == 1) {
    error_log("verify_otp.php - OTP already used for: {$email}");
    echo "ALREADY_USED";
    $stmt->close();
    $conn->close();
    exit;
}

// ─────────────────────────────────────────────────
// Check if OTP Expired
// ─────────────────────────────────────────────────
$now = new DateTime();
$expires = new DateTime($row['expires_at']);

if ($now > $expires) {
    $nowStr = $now->format('Y-m-d H:i:s');
    $expStr = $expires->format('Y-m-d H:i:s');
    error_log("verify_otp.php - OTP expired. Now: {$nowStr}, Expires: {$expStr}");
    echo "EXPIRED";
    $stmt->close();
    $conn->close();
    exit;
}

// ─────────────────────────────────────────────────
// Mark OTP as Verified
// ─────────────────────────────────────────────────
$update = $conn->prepare(
    "UPDATE email_verifications 
     SET verified = 1 
     WHERE email = ? AND otp = ?"
);

if (!$update) {
    error_log("verify_otp.php - Update prepare failed: " . $conn->error);
    echo "DB_ERROR";
    $stmt->close();
    $conn->close();
    exit;
}

$update->bind_param("ss", $email, $otp);

if ($update->execute()) {
    error_log("verify_otp.php - ✓ SUCCESS! OTP verified for: {$email}");
    echo "VERIFIED";
} else {
    error_log("verify_otp.php - Update execution failed: " . $update->error);
    echo "DB_ERROR";
}

// ─────────────────────────────────────────────────
// Cleanup
// ─────────────────────────────────────────────────
$update->close();
$stmt->close();
$conn->close();
?>