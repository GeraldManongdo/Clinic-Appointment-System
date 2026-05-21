<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ================================================
// send_otp.php
// ================================================
// REQUIREMENTS:
//   composer require phpmailer/phpmailer
//   A Gmail account with 2FA enabled + App Password
// ================================================

error_reporting(E_ALL);
ini_set('display_errors', 0);   // KEEP OFF in production (errors break the JS check)
ini_set('log_errors', 1);       // Errors go to PHP error log instead

header('Content-Type: text/plain');

require __DIR__ . '/../../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ── Database ──────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "clinic");

if ($conn->connect_error) {
    error_log("DB connect error: " . $conn->connect_error);
    echo "DB_ERROR";
    exit;
}

// ── Input validation ──────────────────────────────
if (empty($_POST['email'])) {
    echo "NO_EMAIL";
    exit;
}

$email = trim($_POST['email']);

// Basic email format check
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "INVALID_EMAIL";
    exit;
}

$email = $conn->real_escape_string($email);

// ── Generate OTP ──────────────────────────────────
$otp    = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// ── Delete old OTPs for this email ────────────────
$conn->query("DELETE FROM email_verifications WHERE email = '$email'");

// ── Insert new OTP ────────────────────────────────
$insert = $conn->query(
    "INSERT INTO email_verifications (email, otp, expires_at, verified)
     VALUES ('$email', '$otp', '$expiry', 0)"
);

if (!$insert) {
    error_log("OTP insert error: " . $conn->error);
    echo "DB_INSERT_ERROR";
    exit;
}

// ── Send Email via PHPMailer ──────────────────────
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'walfridoteodoro@gmail.com';   // ← Your Gmail
    $mail->Password   = 'hemkrxvfvjmvbfgp';            // ← 16-char App Password (no spaces)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ]
    ];

    // Recipients
    $mail->setFrom('walfridoteodoro@gmail.com', 'Clinic Booking');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Clinic Booking OTP';
    $mail->Body    = "
        <div style='font-family:sans-serif;max-width:480px;margin:0 auto;padding:24px;'>
            <h2 style='color:#4f2d8a;margin-bottom:8px;'>Clinic Appointment Booking</h2>
            <p style='color:#555;'>Your One-Time Password (OTP) is:</p>
            <div style='font-size:2.5rem;font-weight:700;letter-spacing:.4rem;
                        color:#4f2d8a;background:#ede8f8;border-radius:10px;
                        padding:16px 24px;display:inline-block;margin:12px 0;'>
                {$otp}
            </div>
            <p style='color:#555;font-size:.9rem;'>
                This OTP expires in <strong>5 minutes</strong>. Do not share it with anyone.
            </p>
            <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
            <p style='color:#aaa;font-size:.8rem;'>©2026 Clinic</p>
        </div>
    ";
    $mail->AltBody = "Your Clinic Booking OTP is: {$otp}\n\nThis OTP expires in 5 minutes.";

    $mail->send();
    echo "OTP_SENT";

} catch (Exception $e) {
    error_log("PHPMailer error: " . $mail->ErrorInfo);
    echo "MAIL_ERROR: " . $mail->ErrorInfo;
}
?>