<?php
/**
 * send_confirmation.php
 * =====================
 * Called when patient submits booking form.
 * Saves appointment as STATUS='pending' — NO email sent yet.
 * Admin must approve via dashboard → THEN confirmation email is sent.
 */

header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// ── DB Connect ────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "clinic");
if ($conn->connect_error) {
    echo "ERROR: DB connection failed: " . $conn->connect_error;
    exit;
}

// ── Auto-create tables if not yet created ─────────
// (this is the safest approach — no separate setup step required)
$conn->query("
    CREATE TABLE IF NOT EXISTS appointments (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        patient_name         VARCHAR(200)  NOT NULL,
        email                VARCHAR(255)  NOT NULL,
        phone                VARCHAR(50),
        appointment_date     DATE NOT NULL,
        appointment_time     VARCHAR(20) NOT NULL,
        appointment_datetime VARCHAR(100) NOT NULL,
        payment_method       ENUM('gcash','maya') NOT NULL,
        payment_ref          VARCHAR(100),
        receipt_image        VARCHAR(255),
        sender_name          VARCHAR(200),
        booking_ref          VARCHAR(50) UNIQUE,
        amount               DECIMAL(10,2) DEFAULT 500.00,
        status               ENUM('pending','confirmed','rejected','cancelled') DEFAULT 'pending',
        admin_notes          TEXT,
        cancel_reason        TEXT,
        created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        confirmed_at         TIMESTAMP NULL,
        INDEX idx_status     (status),
        INDEX idx_email      (email),
        INDEX idx_ref        (booking_ref),
        INDEX idx_date       (appointment_date),
        INDEX idx_datetime   (appointment_date, appointment_time)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$conn->query("
    CREATE TABLE IF NOT EXISTS blocked_dates (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        block_date  DATE NOT NULL UNIQUE,
        reason      VARCHAR(255),
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_date (block_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ── Get POST data ─────────────────────────────────
$name          = trim($_POST['name']          ?? '');
$email         = trim($_POST['email']         ?? '');
$phone         = trim($_POST['phone']         ?? '');
$datetime      = trim($_POST['datetime']      ?? '');
$paymentmethod = strtolower(trim($_POST['paymentmethod'] ?? ''));
$paymentref    = trim($_POST['paymentref']    ?? '');
$ref           = trim($_POST['ref']           ?? '');

// Validate required fields
if (!$name || !$email || !$datetime || !$paymentmethod || !$paymentref || !$ref) {
    $missing = [];
    if (!$name)          $missing[] = 'name';
    if (!$email)         $missing[] = 'email';
    if (!$datetime)      $missing[] = 'datetime';
    if (!$paymentmethod) $missing[] = 'paymentmethod';
    if (!$paymentref)    $missing[] = 'paymentref';
    if (!$ref)           $missing[] = 'ref';
    echo "ERROR: Missing required fields: " . implode(', ', $missing);
    $conn->close(); exit;
}

// Sanitise payment method
if (!in_array($paymentmethod, ['gcash','maya'])) {
    $paymentmethod = 'gcash';
}

// ── Parse date & time from "February 25, 2026 at 9:00 AM" ───
$parts = explode(" at ", $datetime);
if (count($parts) === 2) {
    try {
        $date_obj = new DateTime(trim($parts[0]));
        $appointment_date = $date_obj->format('Y-m-d');
        $appointment_time = trim($parts[1]);
    } catch (Exception $e) {
        $appointment_date = date('Y-m-d');
        $appointment_time = '9:00 AM';
    }
} else {
    $appointment_date = date('Y-m-d');
    $appointment_time = '9:00 AM';
}

// ── Handle receipt screenshot upload ─────────────
$receipt_image = null;
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['receipt'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','webp','heic'])) {
        $upload_dir = __DIR__ . '/uploads/receipts/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = 'rcpt_' . preg_replace('/[^a-zA-Z0-9]/', '', $ref) . '_' . time() . '.' . $ext;
        $target   = $upload_dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            $receipt_image = 'uploads/receipts/' . $filename;
        }
    }
}

// ── Duplicate check ────────────────────────────────
$chk = $conn->prepare("SELECT id FROM appointments WHERE booking_ref = ?");
$chk->bind_param("s", $ref);
$chk->execute();
$chk->store_result();
if ($chk->num_rows > 0) {
    echo "ERROR: Duplicate booking reference";
    $chk->close(); $conn->close(); exit;
}
$chk->close();

// ── Insert as PENDING ──────────────────────────────
$sender_name = ''; // No longer collected from patient

$stmt = $conn->prepare("
    INSERT INTO appointments (
        patient_name, email, phone,
        appointment_date, appointment_time, appointment_datetime,
        payment_method, payment_ref, sender_name, receipt_image,
        booking_ref, amount, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 500.00, 'pending')
");

if (!$stmt) {
    echo "ERROR: Prepare failed: " . $conn->error;
    $conn->close(); exit;
}

$stmt->bind_param(
    "sssssssssss",
    $name, $email, $phone,
    $appointment_date, $appointment_time, $datetime,
    $paymentmethod, $paymentref, $sender_name, $receipt_image,
    $ref
);

if ($stmt->execute()) {
    echo "BOOKING_SUBMITTED";
    error_log("Booking saved as pending: $ref | Patient: $name | $datetime");
} else {
    echo "ERROR: Insert failed: " . $stmt->error;
    error_log("Booking insert error: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>