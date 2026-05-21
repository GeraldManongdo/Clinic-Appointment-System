<?php
/**
 * admin_api.php - COMPLETE VERSION
 * ========================================
 * Actions:
 *   GET  overview                → stats + recent appointments
 *   GET  appointments            → all appointments
 *   GET  past_appointments       → past appointments
 *   GET  blocked_dates           → list blocked dates
 *   GET  blocked_time_slots      → list blocked time slots
 *   GET  available_slots         → available time slots for a date
 *   GET  get_customizer          → load saved customizer settings
 *   GET  get_features            → load Why Choose Us features
 *   GET  get_services            → load Core Services
 *   GET  get_messages            → load contact messages (admin)
 *   GET  get_testimonials        → load approved testimonials (public)
 *   GET  get_pending_testimonials→ load pending testimonials (admin)
 *   POST approve                 → approve appointment + send email
 *   POST reject                  → reject appointment + send email
 *   POST cancel                  → cancel appointment + send email
 *   POST bulk_cancel             → cancel multiple appointments
 *   POST block_date              → block a date
 *   POST unblock_date            → unblock a date
 *   POST block_time_slots        → block specific time slots on a date
 *   POST unblock_time_slot       → unblock a single time slot
 *   POST batch_block_range       → block a range of dates
 *   POST batch_block_recurring   → block recurring weekdays in a range
 *   POST delete_past_selected    → delete selected past appointments
 *   POST delete_past_all         → delete all past appointments
 *   POST upload_qr               → upload QR code image
 *   POST upload_image            → upload web image (with DB storage)
 *   POST save_payment_settings   → save payment fee + instructions
 *   POST save_colors             → save theme colors
 *   POST save_customizer         → save web customizer settings
 *   POST save_features           → save Why Choose Us feature cards
 *   POST save_services           → save Core Services cards
 *   POST submit_message          → public contact form submission (no auth)
 *   POST approve_testimonial     → make a message visible as testimonial
 *   POST reject_testimonial      → remove/hide a testimonial
 *   POST delete_message          → delete a contact message
 *   POST logout                  → sign out admin
 */

session_start();

// ── Auth check (skip for logout) ────────────────
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'OK']);
    exit;
}

// Public actions — no admin auth required
$public_actions = ['submit_message', 'get_testimonials', 'get_customizer', 'get_features', 'get_services'];
if (!in_array($action, $public_actions)) {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: application/json');

require __DIR__ . '/../../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer-master/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ── Email Config ────────────────────────────────
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_USER',     'walfridoteodoro@gmail.com');
define('SMTP_PASS',     'hemkrxvfvjmvbfgp');
define('SMTP_FROM',     'walfridoteodoro@gmail.com');
define('SMTP_FROM_NAME','Clinic Booking System');

// ── DB Connect ───────────────────────────────────
$conn = new mysqli("localhost", "root", "", "clinic");
if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

// ── Auto-create tables ───────────────────────────
$conn->query("
    CREATE TABLE IF NOT EXISTS appointments (
        id                  INT AUTO_INCREMENT PRIMARY KEY,
        patient_name        VARCHAR(200)  NOT NULL,
        email               VARCHAR(255)  NOT NULL,
        phone               VARCHAR(50),
        appointment_date    DATE NOT NULL,
        appointment_time    VARCHAR(20) NOT NULL,
        appointment_datetime VARCHAR(100) NOT NULL,
        payment_method      ENUM('gcash','maya') NOT NULL,
        payment_ref         VARCHAR(100),
        receipt_image       VARCHAR(255),
        sender_name         VARCHAR(200),
        booking_ref         VARCHAR(50) UNIQUE,
        amount              DECIMAL(10,2) DEFAULT 500.00,
        status              ENUM('pending','confirmed','rejected','cancelled') DEFAULT 'pending',
        admin_notes         TEXT,
        cancel_reason       TEXT,
        created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        confirmed_at        TIMESTAMP NULL,
        INDEX idx_status    (status),
        INDEX idx_email     (email),
        INDEX idx_ref       (booking_ref),
        INDEX idx_date      (appointment_date),
        INDEX idx_datetime  (appointment_date, appointment_time)
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

$conn->query("
    CREATE TABLE IF NOT EXISTS blocked_time_slots (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        block_date  DATE NOT NULL,
        slot_time   VARCHAR(20) NOT NULL,
        reason      VARCHAR(255),
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_slot (block_date, slot_time),
        INDEX idx_date (block_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$conn->query("
    CREATE TABLE IF NOT EXISTS site_settings (
        setting_key   VARCHAR(100) PRIMARY KEY,
        setting_value LONGTEXT,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ── New tables for features, services, messages ──
$conn->query("
    CREATE TABLE IF NOT EXISTS site_features (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        icon        VARCHAR(20) NOT NULL DEFAULT '⭐',
        title       VARCHAR(200) NOT NULL,
        description TEXT NOT NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        is_active   TINYINT(1) NOT NULL DEFAULT 1,
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$conn->query("
    CREATE TABLE IF NOT EXISTS site_services (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        icon        VARCHAR(20) NOT NULL DEFAULT '🏥',
        badge       VARCHAR(100) NOT NULL DEFAULT 'General',
        title       VARCHAR(200) NOT NULL,
        description TEXT NOT NULL,
        image_path  VARCHAR(500) DEFAULT NULL,
        sort_order  INT NOT NULL DEFAULT 0,
        is_active   TINYINT(1) NOT NULL DEFAULT 1,
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$conn->query("
    CREATE TABLE IF NOT EXISTS contact_messages (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        sender_name     VARCHAR(200) NOT NULL,
        sender_email    VARCHAR(255) NOT NULL,
        subject         VARCHAR(300) NOT NULL,
        message         TEXT NOT NULL,
        is_read         TINYINT(1) NOT NULL DEFAULT 0,
        is_testimonial  TINYINT(1) NOT NULL DEFAULT 0,
        testimonial_rating INT NOT NULL DEFAULT 5,
        is_visible      TINYINT(1) NOT NULL DEFAULT 0,
        created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ── Helper: get/set settings ─────────────────────
function get_setting($conn, $key, $default = '') {
    $stmt = $conn->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ? $row['setting_value'] : $default;
}

function set_setting($conn, $key, $value) {
    $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    $stmt->bind_param("sss", $key, $value, $value);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

// ── Helper: send email ───────────────────────────
function send_mail($to_email, $to_name, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host        = SMTP_HOST;
        $mail->SMTPAuth    = true;
        $mail->Username    = SMTP_USER;
        $mail->Password    = SMTP_PASS;
        $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port        = 587;
        $mail->SMTPOptions = ['ssl' => ['verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true]];
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        return false;
    }
}

// ══════════════════════════════════════════════════
// OVERVIEW
// ══════════════════════════════════════════════════
if ($action === 'overview') {
    $today = date('Y-m-d');
    $stats = $conn->query("
        SELECT
            COUNT(*) AS total,
            SUM(status='pending')   AS pending,
            SUM(status='confirmed') AS confirmed,
            SUM(status='rejected')  AS rejected,
            SUM(status='cancelled') AS cancelled,
            SUM(appointment_date='$today') AS today
        FROM appointments
    ")->fetch_assoc();

    $recent = [];
    $res = $conn->query("
        SELECT id, patient_name, email, appointment_datetime, payment_method, payment_ref, status,
               DATE_FORMAT(created_at,'%b %d %Y %h:%i %p') AS created
        FROM appointments ORDER BY created_at DESC LIMIT 8
    ");
    while ($row = $res->fetch_assoc()) $recent[] = $row;

    echo json_encode(['stats' => $stats, 'recent' => $recent]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// ALL APPOINTMENTS
// ══════════════════════════════════════════════════
if ($action === 'appointments') {
    $filter = $_GET['filter'] ?? 'all';
    $where  = "1=1";
    if ($filter !== 'all') {
        $where = "status = '" . $conn->real_escape_string($filter) . "'";
    }

    $rows = [];
    $res = $conn->query("
        SELECT id, patient_name, email, phone, appointment_date, appointment_time, appointment_datetime,
               payment_method, payment_ref, sender_name, receipt_image,
               booking_ref, amount, status, admin_notes, cancel_reason,
               DATE_FORMAT(created_at,'%b %d %Y %h:%i %p') AS created_at,
               DATE_FORMAT(confirmed_at,'%b %d %Y %h:%i %p') AS confirmed_at
        FROM appointments WHERE {$where}
        ORDER BY
            CASE status WHEN 'pending' THEN 1 WHEN 'confirmed' THEN 2 WHEN 'rejected' THEN 3 WHEN 'cancelled' THEN 4 END,
            appointment_date ASC, appointment_time ASC
    ");
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode(['appointments' => $rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// PAST APPOINTMENTS
// ══════════════════════════════════════════════════
if ($action === 'past_appointments') {
    $from  = $_GET['from'] ?? '';
    $to    = $_GET['to']   ?? '';
    $today = date('Y-m-d');

    $where = "appointment_date < '$today'";
    if ($from) $where .= " AND appointment_date >= '" . $conn->real_escape_string($from) . "'";
    if ($to)   $where .= " AND appointment_date <= '" . $conn->real_escape_string($to)   . "'";

    $rows = [];
    $res = $conn->query("
        SELECT id, patient_name, email, phone, appointment_datetime, appointment_date,
               booking_ref, status, amount, payment_method, payment_ref,
               DATE_FORMAT(created_at,'%b %d %Y %h:%i %p') AS created_at
        FROM appointments WHERE $where
        ORDER BY appointment_date DESC, created_at DESC
    ");
    if ($res) { while ($r = $res->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['appointments' => $rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET BLOCKED DATES
// ══════════════════════════════════════════════════
if ($action === 'blocked_dates') {
    $rows = [];
    $res = $conn->query("
        SELECT id, block_date, reason,
               DATE_FORMAT(created_at,'%b %d %Y') AS created_at
        FROM blocked_dates ORDER BY block_date ASC
    ");
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode(['blocked_dates' => $rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET BLOCKED TIME SLOTS
// ══════════════════════════════════════════════════
if ($action === 'blocked_time_slots') {
    $rows = [];
    $res = $conn->query("
        SELECT id, block_date, slot_time, reason
        FROM blocked_time_slots
        ORDER BY block_date ASC, slot_time ASC
    ");
    if ($res) { while ($r = $res->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['blocked_slots' => $rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET AVAILABLE SLOTS FOR A DATE
// ══════════════════════════════════════════════════
if ($action === 'available_slots') {
    $date = $_GET['date'] ?? '';
    if (!$date) { echo json_encode(['error' => 'Date required']); $conn->close(); exit; }

    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM blocked_dates WHERE block_date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $blocked = $stmt->get_result()->fetch_assoc()['cnt'] > 0;
    $stmt->close();

    if ($blocked) {
        echo json_encode(['blocked' => true, 'available_slots' => []]);
        $conn->close(); exit;
    }

    $all_slots = ['8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM',
                  '1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM'];

    $stmt = $conn->prepare("SELECT appointment_time FROM appointments WHERE appointment_date=? AND status='confirmed'");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $res = $stmt->get_result();
    $booked = [];
    while ($row = $res->fetch_assoc()) $booked[] = $row['appointment_time'];
    $stmt->close();

    $stmt2 = $conn->prepare("SELECT slot_time FROM blocked_time_slots WHERE block_date=?");
    $stmt2->bind_param("s", $date);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row = $res2->fetch_assoc()) $booked[] = $row['slot_time'];
    $stmt2->close();

    $available = array_values(array_diff($all_slots, $booked));
    echo json_encode(['blocked' => false, 'available_slots' => $available, 'booked_slots' => $booked]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET CUSTOMIZER SETTINGS
// ══════════════════════════════════════════════════
if ($action === 'get_customizer') {
    $raw  = get_setting($conn, 'customizer_data', '{}');
    $data = json_decode($raw, true) ?: [];
    // Also merge in saved image paths
    $hero_img = get_setting($conn, 'img_hero', '');
    if ($hero_img) $data['hero_img'] = $hero_img;
    echo json_encode(['status' => 'OK', 'data' => $data]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// APPROVE APPOINTMENT
// ══════════════════════════════════════════════════
if ($action === 'approve') {
    $id    = intval($_POST['id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID']); $conn->close(); exit; }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id=? AND status='pending'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $appt = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$appt) { echo json_encode(['status'=>'ERROR','message'=>'Not found or not pending']); $conn->close(); exit; }

    $now = date('Y-m-d H:i:s');
    $upd = $conn->prepare("UPDATE appointments SET status='confirmed', confirmed_at=?, admin_notes=? WHERE id=?");
    $upd->bind_param("ssi", $now, $notes, $id);
    if (!$upd->execute()) { echo json_encode(['status'=>'ERROR','message'=>'DB error']); $upd->close(); $conn->close(); exit; }
    $upd->close();

    $name    = htmlspecialchars($appt['patient_name']);
    $dt      = htmlspecialchars($appt['appointment_datetime']);
    $ref     = htmlspecialchars($appt['booking_ref']);
    $method  = strtoupper(htmlspecialchars($appt['payment_method']));
    $payref  = htmlspecialchars($appt['payment_ref']);

    $body = "
    <div style='max-width:580px;margin:32px auto;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif;'>
      <div style='background:linear-gradient(135deg,#2d8a6b,#217A4B);padding:32px;border-radius:16px 16px 0 0;text-align:center'>
        <h1 style='color:#fff;margin:0;font-size:1.6rem;font-weight:700'>🏥 Clinic</h1>
        <p style='color:rgba(255,255,255,.85);margin:8px 0 0;font-size:1rem'>Your Appointment is Confirmed!</p>
      </div>
      <div style='background:#fff;border:2px solid #e8e3dc;border-top:none;border-radius:0 0 16px 16px;padding:32px;'>
        <div style='background:#e8f5ee;border-left:4px solid #2d8a6b;border-radius:10px;padding:18px 20px;margin-bottom:24px;'>
          <p style='margin:0;font-size:.85rem;color:#6b6b7e;font-weight:600;text-transform:uppercase;letter-spacing:.05em'>Booking Reference</p>
          <p style='margin:8px 0 0;font-size:1.8rem;font-weight:800;color:#2d8a6b;font-family:monospace;letter-spacing:.08em'>{$ref}</p>
        </div>
        <p style='margin:0 0 20px;font-size:1rem;line-height:1.6;color:#1a1a2e'>
          Dear <strong>{$name}</strong>,<br><br>
          Your appointment has been <strong style='color:#2d8a6b'>confirmed</strong> by our clinic staff.
        </p>
        <table style='width:100%;border-collapse:collapse;margin-bottom:24px'>
          <tr><td style='padding:10px 0;border-bottom:1px solid #e8e3dc;font-size:.85rem;color:#6b6b7e;font-weight:600'>📅 Date & Time</td>
              <td style='padding:10px 0;border-bottom:1px solid #e8e3dc;font-size:.9rem;font-weight:600;text-align:right'>{$dt}</td></tr>
          <tr><td style='padding:10px 0;border-bottom:1px solid #e8e3dc;font-size:.85rem;color:#6b6b7e;font-weight:600'>💳 Payment</td>
              <td style='padding:10px 0;border-bottom:1px solid #e8e3dc;font-size:.9rem;font-weight:600;text-align:right'>{$method}</td></tr>
          <tr><td style='padding:10px 0;font-size:.85rem;color:#6b6b7e;font-weight:600'>🧾 Ref #</td>
              <td style='padding:10px 0;font-size:.9rem;font-weight:600;text-align:right;font-family:monospace'>{$payref}</td></tr>
        </table>
        <div style='background:#fef7e6;border-radius:10px;padding:16px 20px;margin-bottom:24px'>
          <p style='margin:0 0 10px;font-size:.85rem;font-weight:700;color:#c6860a;text-transform:uppercase'>⚠️ Reminders</p>
          <ul style='margin:0;padding-left:20px;font-size:.88rem;line-height:1.7;color:#1a1a2e'>
            <li>Arrive <strong>10 minutes early</strong></li>
            <li>Bring a <strong>valid government ID</strong></li>
            <li>Bring this <strong>reference number</strong></li>
          </ul>
        </div>
        <div style='text-align:center;padding-top:20px;border-top:1px solid #e8e3dc'>
          <p style='margin:0;font-size:.8rem;color:#8a847c'><strong>Clinic Medical Center</strong><br>📞 (02) 1234-5678 · 📧 clinic@example.com</p>
        </div>
      </div>
    </div>";

    $sent = send_mail($appt['email'], $appt['patient_name'], "✅ Appointment Confirmed — {$ref}", $body);
    echo json_encode(['status'=>'OK','message'=> $sent ? 'Approved and email sent' : 'Approved but email failed']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// REJECT APPOINTMENT
// ══════════════════════════════════════════════════
if ($action === 'reject') {
    $id     = intval($_POST['id'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    if (!$id || !$reason) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID or reason']); $conn->close(); exit; }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id=? AND status='pending'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $appt = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$appt) { echo json_encode(['status'=>'ERROR','message'=>'Not found or not pending']); $conn->close(); exit; }

    $upd = $conn->prepare("UPDATE appointments SET status='rejected', admin_notes=? WHERE id=?");
    $upd->bind_param("si", $reason, $id);
    if (!$upd->execute()) { echo json_encode(['status'=>'ERROR','message'=>'DB error']); $upd->close(); $conn->close(); exit; }
    $upd->close();

    $name = htmlspecialchars($appt['patient_name']);
    $dt   = htmlspecialchars($appt['appointment_datetime']);
    $ref  = htmlspecialchars($appt['booking_ref']);
    $rsn  = htmlspecialchars($reason);

    $body = "
    <div style='max-width:580px;margin:32px auto;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif;'>
      <div style='background:#c0392b;padding:32px;border-radius:16px 16px 0 0;text-align:center'>
        <h1 style='color:#fff;margin:0;font-size:1.6rem;font-weight:700'>🏥 Clinic</h1>
        <p style='color:rgba(255,255,255,.85);margin:8px 0 0'>Appointment Not Approved</p>
      </div>
      <div style='background:#fff;border:2px solid #e8e3dc;border-top:none;border-radius:0 0 16px 16px;padding:32px;'>
        <p style='margin:0 0 20px;font-size:1rem;line-height:1.6;color:#1a1a2e'>
          Dear <strong>{$name}</strong>,<br><br>We could not approve your appointment for <strong>{$dt}</strong> (Ref: <code>{$ref}</code>).
        </p>
        <div style='background:#fff5e6;border-radius:10px;padding:16px 20px;margin-bottom:24px'>
          <p style='margin:0 0 8px;font-size:.85rem;font-weight:700;color:#c6860a;text-transform:uppercase'>Reason</p>
          <p style='margin:0;font-size:.9rem;color:#1a1a2e;line-height:1.6'>{$rsn}</p>
        </div>
        <div style='text-align:center;padding-top:20px;border-top:1px solid #e8e3dc'>
          <p style='margin:0;font-size:.8rem;color:#8a847c'><strong>Clinic Medical Center</strong><br>📞 (02) 1234-5678 · 📧 clinic@example.com</p>
        </div>
      </div>
    </div>";

    $sent = send_mail($appt['email'], $appt['patient_name'], "❌ Appointment Not Approved — {$ref}", $body);
    echo json_encode(['status'=>'OK','message'=> $sent ? 'Rejected and email sent' : 'Rejected but email failed']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// CANCEL APPOINTMENT
// ══════════════════════════════════════════════════
if ($action === 'cancel') {
    $id     = intval($_POST['id'] ?? 0);
    $reason = trim($_POST['reason'] ?? '');

    if (!$id || !$reason) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID or reason']); $conn->close(); exit; }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id=? AND status NOT IN ('cancelled','rejected')");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $appt = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$appt) { echo json_encode(['status'=>'ERROR','message'=>'Not found']); $conn->close(); exit; }

    $upd = $conn->prepare("UPDATE appointments SET status='cancelled', cancel_reason=? WHERE id=?");
    $upd->bind_param("si", $reason, $id);
    $upd->execute(); $upd->close();

    $name = htmlspecialchars($appt['patient_name']);
    $dt   = htmlspecialchars($appt['appointment_datetime']);
    $ref  = htmlspecialchars($appt['booking_ref']);
    $rsn  = htmlspecialchars($reason);

    $body = "
    <div style='max-width:580px;margin:32px auto;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif;'>
      <div style='background:#7f8c8d;padding:32px;border-radius:16px 16px 0 0;text-align:center'>
        <h1 style='color:#fff;margin:0;font-size:1.6rem;font-weight:700'>🏥 Clinic</h1>
        <p style='color:rgba(255,255,255,.85);margin:8px 0 0'>Appointment Cancelled</p>
      </div>
      <div style='background:#fff;border:2px solid #e8e3dc;border-top:none;border-radius:0 0 16px 16px;padding:32px;'>
        <p style='margin:0 0 20px;font-size:1rem;line-height:1.6;color:#1a1a2e'>
          Dear <strong>{$name}</strong>,<br><br>Your appointment on <strong>{$dt}</strong> (Ref: <code>{$ref}</code>) has been cancelled.
        </p>
        <div style='background:#f1f5f9;border-radius:10px;padding:16px 20px;margin-bottom:24px'>
          <p style='margin:0 0 8px;font-size:.85rem;font-weight:700;color:#64748b;text-transform:uppercase'>Reason</p>
          <p style='margin:0;font-size:.9rem;color:#1a1a2e;line-height:1.6'>{$rsn}</p>
        </div>
        <div style='text-align:center;padding-top:20px;border-top:1px solid #e8e3dc'>
          <p style='margin:0;font-size:.8rem;color:#8a847c'><strong>Clinic Medical Center</strong><br>📞 (02) 1234-5678 · 📧 clinic@example.com</p>
        </div>
      </div>
    </div>";

    $sent = send_mail($appt['email'], $appt['patient_name'], "🚫 Appointment Cancelled — {$ref}", $body);
    echo json_encode(['status'=>'OK','message'=> $sent ? 'Cancelled and email sent' : 'Cancelled but email failed']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// BULK CANCEL
// ══════════════════════════════════════════════════
if ($action === 'bulk_cancel') {
    $ids_raw = trim($_POST['ids'] ?? '');
    $reason  = trim($_POST['reason'] ?? '');

    if (!$ids_raw || !$reason) { echo json_encode(['status'=>'ERROR','message'=>'Missing IDs or reason']); $conn->close(); exit; }

    $ids = array_filter(array_map('intval', explode(',', $ids_raw)), fn($i) => $i > 0);
    if (empty($ids)) { echo json_encode(['status'=>'ERROR','message'=>'No valid IDs']); $conn->close(); exit; }

    $cancelled = 0;
    foreach ($ids as $id) {
        $stmt = $conn->prepare("SELECT * FROM appointments WHERE id=? AND status NOT IN ('cancelled','rejected')");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $appt = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$appt) continue;

        $upd = $conn->prepare("UPDATE appointments SET status='cancelled', cancel_reason=? WHERE id=?");
        $upd->bind_param("si", $reason, $id);
        $upd->execute(); $upd->close();
        $cancelled++;

        // Send cancellation email
        $name = htmlspecialchars($appt['patient_name']);
        $dt   = htmlspecialchars($appt['appointment_datetime']);
        $ref  = htmlspecialchars($appt['booking_ref']);
        $rsn  = htmlspecialchars($reason);
        $body = "<div style='max-width:580px;margin:32px auto;font-family:sans-serif;padding:24px;background:#fff;border-radius:12px;border:1px solid #e2e8f0'>
          <h2 style='color:#dc2626'>🚫 Appointment Cancelled</h2>
          <p>Dear <strong>{$name}</strong>,</p>
          <p>Your appointment on <strong>{$dt}</strong> (Ref: <code>{$ref}</code>) has been cancelled.</p>
          <p><strong>Reason:</strong> {$rsn}</p>
          <p style='color:#64748b;font-size:.85rem;margin-top:20px'>Clinic Medical Center · clinic@example.com</p>
        </div>";
        send_mail($appt['email'], $appt['patient_name'], "🚫 Appointment Cancelled — {$ref}", $body);
    }

    echo json_encode(['status'=>'OK','message'=>"{$cancelled} appointment(s) cancelled"]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// BLOCK DATE
// ══════════════════════════════════════════════════
if ($action === 'block_date') {
    $date   = trim($_POST['date'] ?? '');
    $reason = trim($_POST['reason'] ?? 'Clinic closed');
    if (!$date) { echo json_encode(['status'=>'ERROR','message'=>'Date required']); $conn->close(); exit; }

    $stmt = $conn->prepare("INSERT INTO blocked_dates (block_date, reason) VALUES (?, ?) ON DUPLICATE KEY UPDATE reason=?");
    $stmt->bind_param("sss", $date, $reason, $reason);
    echo $stmt->execute()
        ? json_encode(['status'=>'OK','message'=>'Date blocked'])
        : json_encode(['status'=>'ERROR','message'=>'Failed to block date']);
    $stmt->close(); $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// UNBLOCK DATE
// ══════════════════════════════════════════════════
if ($action === 'unblock_date') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'ID required']); $conn->close(); exit; }

    $stmt = $conn->prepare("DELETE FROM blocked_dates WHERE id=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute()
        ? json_encode(['status'=>'OK','message'=>'Date unblocked'])
        : json_encode(['status'=>'ERROR','message'=>'Failed']);
    $stmt->close(); $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// BLOCK TIME SLOTS
// ══════════════════════════════════════════════════
if ($action === 'block_time_slots') {
    $date   = trim($_POST['date'] ?? '');
    $slots  = json_decode($_POST['slots'] ?? '[]', true);
    $reason = trim($_POST['reason'] ?? 'Blocked');

    if (!$date || empty($slots)) { echo json_encode(['status'=>'ERROR','message'=>'Date and slots required']); $conn->close(); exit; }

    $blocked = 0;
    foreach ($slots as $slot) {
        $slot = trim($slot);
        if (!$slot) continue;
        $stmt = $conn->prepare("INSERT IGNORE INTO blocked_time_slots (block_date, slot_time, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $date, $slot, $reason);
        if ($stmt->execute()) $blocked++;
        $stmt->close();
    }

    echo json_encode(['status'=>'OK','message'=>"{$blocked} slot(s) blocked"]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// UNBLOCK TIME SLOT
// ══════════════════════════════════════════════════
if ($action === 'unblock_time_slot') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'ID required']); $conn->close(); exit; }

    $stmt = $conn->prepare("DELETE FROM blocked_time_slots WHERE id=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute()
        ? json_encode(['status'=>'OK','message'=>'Slot unblocked'])
        : json_encode(['status'=>'ERROR','message'=>'Failed']);
    $stmt->close(); $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// BATCH BLOCK — DATE RANGE
// ══════════════════════════════════════════════════
if ($action === 'batch_block_range') {
    $from   = trim($_POST['from'] ?? '');
    $to     = trim($_POST['to'] ?? '');
    $reason = trim($_POST['reason'] ?? 'Clinic closed');

    if (!$from || !$to) { echo json_encode(['status'=>'ERROR','message'=>'Start and end dates required']); $conn->close(); exit; }
    if ($from > $to)    { echo json_encode(['status'=>'ERROR','message'=>'Start must be before end']);    $conn->close(); exit; }

    $current = new DateTime($from);
    $end     = new DateTime($to);
    $blocked = 0;

    while ($current <= $end) {
        $dateStr = $current->format('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO blocked_dates (block_date, reason) VALUES (?, ?) ON DUPLICATE KEY UPDATE reason=?");
        $stmt->bind_param("sss", $dateStr, $reason, $reason);
        if ($stmt->execute()) $blocked++;
        $stmt->close();
        $current->modify('+1 day');
    }

    echo json_encode(['status'=>'OK','message'=>"{$blocked} date(s) blocked"]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// BATCH BLOCK — RECURRING DAYS
// ══════════════════════════════════════════════════
if ($action === 'batch_block_recurring') {
    $from   = trim($_POST['from'] ?? '');
    $to     = trim($_POST['to'] ?? '');
    $days   = json_decode($_POST['days'] ?? '[]', true); // array of 0-6 (Sun-Sat)
    $reason = trim($_POST['reason'] ?? 'Recurring closure');

    if (!$from || !$to)  { echo json_encode(['status'=>'ERROR','message'=>'Date range required']); $conn->close(); exit; }
    if (empty($days))    { echo json_encode(['status'=>'ERROR','message'=>'No days selected']);    $conn->close(); exit; }

    $days    = array_map('intval', $days);
    $current = new DateTime($from);
    $end     = new DateTime($to);
    $blocked = 0;

    while ($current <= $end) {
        $dow = (int)$current->format('w'); // 0=Sun, 6=Sat
        if (in_array($dow, $days)) {
            $dateStr = $current->format('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO blocked_dates (block_date, reason) VALUES (?, ?) ON DUPLICATE KEY UPDATE reason=?");
            $stmt->bind_param("sss", $dateStr, $reason, $reason);
            if ($stmt->execute()) $blocked++;
            $stmt->close();
        }
        $current->modify('+1 day');
    }

    echo json_encode(['status'=>'OK','message'=>"{$blocked} recurring day(s) blocked"]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// DELETE PAST — SELECTED
// ══════════════════════════════════════════════════
if ($action === 'delete_past_selected') {
    $ids_raw = trim($_POST['ids'] ?? '');
    if (!$ids_raw) { echo json_encode(['status'=>'ERROR','message'=>'No IDs']); $conn->close(); exit; }
    $ids = array_filter(array_map('intval', explode(',', $ids_raw)), fn($i) => $i > 0);
    if (empty($ids)) { echo json_encode(['status'=>'ERROR','message'=>'No valid IDs']); $conn->close(); exit; }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $today = date('Y-m-d');
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id IN ($placeholders) AND appointment_date < '$today'");
    $stmt->bind_param($types, ...$ids);
    echo $stmt->execute()
        ? json_encode(['status'=>'OK','message'=> $stmt->affected_rows . ' deleted'])
        : json_encode(['status'=>'ERROR','message'=>'Delete failed: ' . $stmt->error]);
    $stmt->close(); $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// DELETE PAST — ALL
// ══════════════════════════════════════════════════
if ($action === 'delete_past_all') {
    $today  = date('Y-m-d');
    $result = $conn->query("DELETE FROM appointments WHERE appointment_date < '$today'");
    echo $result
        ? json_encode(['status'=>'OK','message'=> $conn->affected_rows . ' deleted'])
        : json_encode(['status'=>'ERROR','message'=>'Failed: ' . $conn->error]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// UPLOAD QR CODE
// ══════════════════════════════════════════════════
/*if ($action === 'upload_qr') {
    $method = $_POST['method'] ?? '';
    if (!in_array($method, ['gcash','maya'])) { echo json_encode(['status'=>'ERROR','message'=>'Invalid method']); exit; }

    $acct = trim($_POST['account_name'] ?? '');
    if ($acct) set_setting($conn, $method . '_account_name', $acct);

    if (!isset($_FILES['qr']) || $_FILES['qr']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'OK','message'=>'Account name saved (no file)']);
        $conn->close(); exit;
    }

    $file = $_FILES['qr'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) { echo json_encode(['status'=>'ERROR','message'=>'Invalid file type']); exit; }

    $dir = __DIR__ . '/images/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $filename = $method . '-qr.' . $ext;
    $target   = $dir . $filename;

    echo move_uploaded_file($file['tmp_name'], $target)
        ? json_encode(['status'=>'OK','message'=>'QR uploaded','filename'=>$filename])
        : json_encode(['status'=>'ERROR','message'=>'Upload failed']);
    $conn->close(); exit;
}
*/

// ══════════════════════════════════════════════════
// UPLOAD QR CODE
// ══════════════════════════════════════════════════
if ($action === 'upload_qr') {
    $method = $_POST['method'] ?? '';
    if (!in_array($method, ['gcash','maya'])) { 
        echo json_encode(['status'=>'ERROR','message'=>'Invalid method']); 
        $conn->close(); exit; 
    }

    $acct   = trim($_POST['account_name'] ?? '');
    $number = trim($_POST['number'] ?? '');

    if ($acct)   set_setting($conn, $method . '_account_name', $acct);
    if ($number) set_setting($conn, $method . '_number', $number);

    if (!isset($_FILES['qr']) || $_FILES['qr']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'OK','message'=>'Account info saved (no image uploaded)']);
        $conn->close(); exit;
    }

    $file = $_FILES['qr'];

    // Validate type
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) { 
        echo json_encode(['status'=>'ERROR','message'=>'Invalid file type']); 
        $conn->close(); exit; 
    }

    // Validate size (max 2MB for QR — they're small)
    if ($file['size'] > 2 * 1024 * 1024) { 
        echo json_encode(['status'=>'ERROR','message'=>'File too large (max 2MB)']); 
        $conn->close(); exit; 
    }

    // Save to disk (keep existing behavior)
    $dir = __DIR__ . '/images/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $filename = $method . '-qr.' . $ext;
    $target   = $dir . $filename;
    move_uploaded_file($file['tmp_name'], $target);

    // ── NEW: Also save as base64 in site_settings ──
    $imageData = file_get_contents($target);
    $mime      = mime_content_type($target);
    $base64    = 'data:' . $mime . ';base64,' . base64_encode($imageData);
    set_setting($conn, $method . '_qr_image', $base64);

    echo json_encode([
        'status'   => 'OK',
        'message'  => strtoupper($method) . ' QR saved and will now appear on the booking page.',
        'filename' => $filename
    ]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET PAYMENT INFO — PUBLIC (used by booking.html)
// ══════════════════════════════════════════════════
if ($action === 'get_payment_info') {
    $keys = [
        'gcash_qr_image', 'gcash_account_name', 'gcash_number',
        'maya_qr_image',  'maya_account_name',  'maya_number',
        'consult_fee'
    ];
    $result = [];
    foreach ($keys as $k) {
        $result[$k] = get_setting($conn, $k, '');
    }
    echo json_encode(['status' => 'OK', 'data' => $result]);
    $conn->close(); exit;
}
// ══════════════════════════════════════════════════
// UPLOAD WEB IMAGE
// ══════════════════════════════════════════════════
if ($action === 'upload_image') {
    $key = trim($_POST['image_key'] ?? '');
    $allowed_keys = ['hero','about','services','logo','doctor','og'];
    if (!in_array($key, $allowed_keys)) { echo json_encode(['status'=>'ERROR','message'=>'Invalid image key']); exit; }

    if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'ERROR','message'=>'No file uploaded']); exit;
    }

    $file = $_FILES['image_file'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) { echo json_encode(['status'=>'ERROR','message'=>'Invalid file type']); exit; }

    // Check file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) { echo json_encode(['status'=>'ERROR','message'=>'File too large (max 5MB)']); exit; }

    $dir = __DIR__ . '/images/web/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $filename = 'web-' . $key . '.' . $ext;
    $target   = $dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        set_setting($conn, 'img_' . $key, 'images/web/' . $filename);
        // Also update hero_img in customizer_data so index.html loads it immediately
        if ($key === 'hero') {
            $raw = get_setting($conn, 'customizer_data', '{}');
            $cdata = json_decode($raw, true) ?: [];
            $cdata['hero_img'] = 'images/web/' . $filename;
            set_setting($conn, 'customizer_data', json_encode($cdata));
        }
        echo json_encode(['status'=>'SAVED','message'=>'Image saved','path'=>'images/web/'.$filename]);
    } else {
        echo json_encode(['status'=>'ERROR','message'=>'Upload failed']);
    }
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SAVE PAYMENT SETTINGS
// ══════════════════════════════════════════════════
if ($action === 'save_payment_settings') {
    $fee          = floatval($_POST['fee'] ?? 500);
    $instructions = trim($_POST['instructions'] ?? '');

    set_setting($conn, 'consult_fee', $fee);
    set_setting($conn, 'payment_instructions', $instructions);

    echo json_encode(['status'=>'SAVED','message'=>'Payment settings saved']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SAVE THEME COLORS
// ══════════════════════════════════════════════════
if ($action === 'save_colors') {
    $colors_raw = $_POST['colors'] ?? '{}';
    $colors = json_decode($colors_raw, true);
    if (!is_array($colors)) { echo json_encode(['status'=>'ERROR','message'=>'Invalid colors data']); $conn->close(); exit; }

    // Validate hex values
    foreach ($colors as $k => $v) {
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $v)) {
            echo json_encode(['status'=>'ERROR','message'=>"Invalid color value for $k"]); $conn->close(); exit;
        }
    }

    set_setting($conn, 'theme_colors', json_encode($colors));
    echo json_encode(['status'=>'SAVED','message'=>'Colors saved']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SAVE CUSTOMIZER SETTINGS  ← THE FIX
// ══════════════════════════════════════════════════
if ($action === 'save_customizer') {
    $data_raw = $_POST['data'] ?? '';
    $decoded = json_decode($data_raw, true);
    if ($decoded === null) {
        echo json_encode(['status'=>'ERROR','message'=>'Invalid JSON data']);
        $conn->close(); exit;
    }

    $allowed = ['name','tagline','doctor','spec','hero-title','hero-sub','about','cta',
                'phone','email','address','open','close','days','duration','max','advance','cancelpolicy'];
    $clean = [];
    foreach ($allowed as $field) {
        if (isset($decoded[$field])) {
            $clean[$field] = htmlspecialchars(strip_tags(trim($decoded[$field])));
        }
    }

    // ── Preserve hero_img set by upload_image ──
    $existing_raw = get_setting($conn, 'customizer_data', '{}');
    $existing = json_decode($existing_raw, true) ?: [];
    if (!empty($existing['hero_img'])) {
        $clean['hero_img'] = $existing['hero_img'];
    }

    if (set_setting($conn, 'customizer_data', json_encode($clean))) {
        echo json_encode(['status'=>'OK','message'=>'Settings saved successfully']);
    } else {
        echo json_encode(['status'=>'ERROR','message'=>'Database save failed']);
    }
    $conn->close(); exit;
}
// ══════════════════════════════════════════════════
// GET FEATURES (Why Choose Us) — PUBLIC
// ══════════════════════════════════════════════════
if ($action === 'get_features') {
    $res = $conn->query("SELECT * FROM site_features WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    // If no rows seeded yet, return defaults
    if (empty($rows)) {
        $rows = [
            ['id'=>0,'icon'=>'📅','title'=>'Smart Online Booking','description'=>'Patients can book 24/7 through a clean, mobile-friendly interface with real-time availability.','sort_order'=>0],
            ['id'=>0,'icon'=>'💳','title'=>'Secure Online Payment','description'=>'Integrated GCash & Maya payment with receipt upload verification and admin approval workflow.','sort_order'=>1],
            ['id'=>0,'icon'=>'✉️','title'=>'Automatic Email Alerts','description'=>'Patients receive confirmation, rejection, and cancellation emails automatically.','sort_order'=>2],
            ['id'=>0,'icon'=>'🛡️','title'=>'Admin Approval System','description'=>'Review and verify each payment before confirming appointments. Full control over your schedule.','sort_order'=>3],
            ['id'=>0,'icon'=>'🚫','title'=>'Flexible Date Blocking','description'=>'Block individual dates, date ranges, or specific time slots with a single click.','sort_order'=>4],
            ['id'=>0,'icon'=>'📊','title'=>'Dashboard & Analytics','description'=>'Get a clear overview of your clinic\'s performance — pending reviews, confirmed appointments, and today\'s schedule.','sort_order'=>5],
        ];
    }
    echo json_encode(['status'=>'OK','data'=>$rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SAVE FEATURES — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'save_features') {
    $data_raw = $_POST['data'] ?? '';
    $decoded  = json_decode($data_raw, true);
    if (!is_array($decoded)) { echo json_encode(['status'=>'ERROR','message'=>'Invalid data']); $conn->close(); exit; }

    // Delete all existing
    $conn->query("DELETE FROM site_features");
    $stmt = $conn->prepare("INSERT INTO site_features (icon,title,description,sort_order,is_active) VALUES (?,?,?,?,1)");
    foreach ($decoded as $i => $item) {
        $icon  = htmlspecialchars(strip_tags(trim($item['icon']  ?? '⭐')));
        $title = htmlspecialchars(strip_tags(trim($item['title'] ?? '')));
        $desc  = htmlspecialchars(strip_tags(trim($item['description'] ?? '')));
        $order = intval($i);
        if ($title) {
            $stmt->bind_param("sssi", $icon, $title, $desc, $order);
            $stmt->execute();
        }
    }
    $stmt->close();
    echo json_encode(['status'=>'OK','message'=>'Features saved']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET SERVICES (Core Services) — PUBLIC
// ══════════════════════════════════════════════════
if ($action === 'get_services') {
    $res = $conn->query("SELECT * FROM site_services WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    if (empty($rows)) {
        $rows = [
            ['id'=>0,'icon'=>'👨‍⚕️','badge'=>'General','title'=>'General Consultation','description'=>'Comprehensive check-ups and consultations for all age groups. Your first step to better health.','image_path'=>null,'sort_order'=>0],
            ['id'=>0,'icon'=>'🧪','badge'=>'Diagnostics','title'=>'Lab & Diagnostics','description'=>'Fast and reliable laboratory services with digital result delivery to your email.','image_path'=>null,'sort_order'=>1],
            ['id'=>0,'icon'=>'🔄','badge'=>'Follow-up','title'=>'Follow-Up Visits','description'=>'Convenient scheduling for follow-up consultations. Track your health progress over time.','image_path'=>null,'sort_order'=>2],
        ];
    }
    echo json_encode(['status'=>'OK','data'=>$rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SAVE SERVICES — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'save_services') {
    $data_raw = $_POST['data'] ?? '';
    $decoded  = json_decode($data_raw, true);
    if (!is_array($decoded)) { echo json_encode(['status'=>'ERROR','message'=>'Invalid data']); $conn->close(); exit; }

    $conn->query("DELETE FROM site_services");
    $stmt = $conn->prepare("INSERT INTO site_services (icon,badge,title,description,image_path,sort_order,is_active) VALUES (?,?,?,?,?,?,1)");
    foreach ($decoded as $i => $item) {
        $icon  = htmlspecialchars(strip_tags(trim($item['icon']  ?? '🏥')));
        $badge = htmlspecialchars(strip_tags(trim($item['badge'] ?? 'General')));
        $title = htmlspecialchars(strip_tags(trim($item['title'] ?? '')));
        $desc  = htmlspecialchars(strip_tags(trim($item['description'] ?? '')));
        $img   = trim($item['image_path'] ?? '');
        $order = intval($i);
        if ($title) { $stmt->bind_param("sssssi", $icon, $badge, $title, $desc, $img, $order); $stmt->execute(); }
    }
    $stmt->close();
    echo json_encode(['status'=>'OK','message'=>'Services saved']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// UPLOAD SERVICE IMAGE — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'upload_service_image') {
    if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status'=>'ERROR','message'=>'No file uploaded']); exit;
    }
    $file = $_FILES['image_file'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) { echo json_encode(['status'=>'ERROR','message'=>'Invalid file type']); exit; }
    if ($file['size'] > 5 * 1024 * 1024) { echo json_encode(['status'=>'ERROR','message'=>'File too large (max 5MB)']); exit; }

    $dir = __DIR__ . '/images/services/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $filename = 'svc-' . time() . '-' . uniqid() . '.' . $ext;
    $target   = $dir . $filename;
    if (move_uploaded_file($file['tmp_name'], $target)) {
        echo json_encode(['status'=>'SAVED','path'=>'images/services/'.$filename]);
    } else {
        echo json_encode(['status'=>'ERROR','message'=>'Upload failed']);
    }
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// SUBMIT CONTACT MESSAGE — PUBLIC (no auth)
// ══════════════════════════════════════════════════
if ($action === 'submit_message') {
    header('Content-Type: application/json');
    $name    = htmlspecialchars(strip_tags(trim($_POST['name']    ?? '')));
    $email   = filter_var(trim($_POST['email']   ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(strip_tags(trim($_POST['subject'] ?? '')));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')));

    if (!$name || !$email || !$subject || !$message) {
        echo json_encode(['status'=>'ERROR','message'=>'All fields are required']); $conn->close(); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status'=>'ERROR','message'=>'Invalid email address']); $conn->close(); exit;
    }
    if (strlen($message) > 3000) {
        echo json_encode(['status'=>'ERROR','message'=>'Message too long (max 3000 chars)']); $conn->close(); exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (sender_name,sender_email,subject,message) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    if ($stmt->execute()) {
        echo json_encode(['status'=>'OK','message'=>'Message sent successfully!']);
    } else {
        echo json_encode(['status'=>'ERROR','message'=>'Failed to save message']);
    }
    $stmt->close(); $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET MESSAGES — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'get_messages') {
    $res = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    // Count unread
    $unread = $conn->query("SELECT COUNT(*) AS cnt FROM contact_messages WHERE is_read=0")->fetch_assoc()['cnt'];
    echo json_encode(['status'=>'OK','data'=>$rows,'unread'=>intval($unread)]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// MARK MESSAGE READ — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'mark_message_read') {
    $id = intval($_POST['id'] ?? 0);
    $conn->query("UPDATE contact_messages SET is_read=1 WHERE id=$id");
    echo json_encode(['status'=>'OK']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// APPROVE TESTIMONIAL — ADMIN (makes msg visible on site)
// ══════════════════════════════════════════════════
if ($action === 'approve_testimonial') {
    $id     = intval($_POST['id'] ?? 0);
    $rating = min(5, max(1, intval($_POST['rating'] ?? 5)));
    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID']); $conn->close(); exit; }
    $stmt = $conn->prepare("UPDATE contact_messages SET is_testimonial=1, is_visible=1, testimonial_rating=?, is_read=1 WHERE id=?");
    $stmt->bind_param("ii", $rating, $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(['status'=>'OK','message'=>'Testimonial approved and now visible on site']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// HIDE/UNAPPROVE TESTIMONIAL — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'reject_testimonial') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID']); $conn->close(); exit; }
    $stmt = $conn->prepare("UPDATE contact_messages SET is_testimonial=0, is_visible=0 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute(); $stmt->close();
    echo json_encode(['status'=>'OK','message'=>'Testimonial hidden from site']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// DELETE MESSAGE — ADMIN
// ══════════════════════════════════════════════════
if ($action === 'delete_message') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['status'=>'ERROR','message'=>'Missing ID']); $conn->close(); exit; }
    $conn->query("DELETE FROM contact_messages WHERE id=$id");
    echo json_encode(['status'=>'OK','message'=>'Message deleted']);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// GET TESTIMONIALS — PUBLIC (only visible ones)
// ══════════════════════════════════════════════════
if ($action === 'get_testimonials') {
    $res = $conn->query("SELECT sender_name, subject, message, testimonial_rating, created_at FROM contact_messages WHERE is_testimonial=1 AND is_visible=1 ORDER BY id DESC LIMIT 6");
    $rows = [];
    while ($r = $res->fetch_assoc()) $rows[] = $r;
    echo json_encode(['status'=>'OK','data'=>$rows]);
    $conn->close(); exit;
}

// ══════════════════════════════════════════════════
// FALLBACK
// ══════════════════════════════════════════════════
echo json_encode(['error' => 'Invalid action: ' . htmlspecialchars($action)]);
$conn->close();
?>