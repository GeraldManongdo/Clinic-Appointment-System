<?php
/**
 * setup.php — Clinic System Diagnostic & Setup
 * =============================================
 * Run this ONCE by visiting: localhost/.../setup.php
 * It will create all required database tables and verify everything is working.
 * DELETE this file after running it.
 */

// ── No auth needed for setup ──────────────────────
error_reporting(E_ALL);
ini_set('display_errors', 1);

$status = [];
$errors = [];

// ── 1. DB Connect ─────────────────────────────────
$conn = @new mysqli("localhost", "root", "", "clinic");
if ($conn->connect_error) {
    die("<h2 style='color:red'>❌ Cannot connect to database 'clinic'</h2>
         <p>Error: " . $conn->connect_error . "</p>
         <p>Make sure XAMPP/WAMP MySQL is running and the <strong>clinic</strong> database exists.</p>
         <p>Create it with: <code>CREATE DATABASE clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></p>");
}
$status[] = "✅ Database connection OK";

// ── 2. Create / update appointments table ─────────
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
$status[] = "✅ appointments table ready";

// ── 3. Create / update blocked_dates table ────────
$conn->query("
    CREATE TABLE IF NOT EXISTS blocked_dates (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        block_date  DATE NOT NULL UNIQUE,
        reason      VARCHAR(255),
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_date (block_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$status[] = "✅ blocked_dates table ready";

// ── 4. Create site_settings table ─────────────────
$conn->query("
    CREATE TABLE IF NOT EXISTS site_settings (
        setting_key   VARCHAR(100) PRIMARY KEY,
        setting_value TEXT,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$status[] = "✅ site_settings table ready";

// ── 5. Check admin_sessions table (for auth) ──────
$conn->query("
    CREATE TABLE IF NOT EXISTS admin_sessions (
        session_id  VARCHAR(128) PRIMARY KEY,
        admin_user  VARCHAR(100),
        created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
$status[] = "✅ admin_sessions table ready";

// ── 6. Count existing data ────────────────────────
$counts = [];
foreach (['appointments','blocked_dates','site_settings'] as $tbl) {
    $r = $conn->query("SELECT COUNT(*) AS n FROM $tbl");
    $n = $r ? $r->fetch_assoc()['n'] : '?';
    $counts[$tbl] = $n;
}

// ── 7. Check uploads directory ────────────────────
$upload_dir = __DIR__ . '/uploads/receipts/';
if (!is_dir($upload_dir)) {
    if (mkdir($upload_dir, 0755, true)) {
        $status[] = "✅ Created uploads/receipts/ directory";
    } else {
        $errors[] = "⚠️ Could not create uploads/receipts/ — create it manually and set permissions to 755";
    }
} else {
    $status[] = "✅ uploads/receipts/ directory exists";
}

// ── 8. Check PHPMailer ────────────────────────────
$mailerPath = __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
if (file_exists($mailerPath)) {
    $status[] = "✅ PHPMailer found";
} else {
    $errors[] = "⚠️ PHPMailer not found at <code>../PHPMailer-master/src/PHPMailer.php</code> — emails will fail. Download from github.com/PHPMailer/PHPMailer";
}

// ── 9. Check required PHP files ──────────────────
$required = [
    'api/admin_api.php', 'auth/admin_auth.php', 'public/admin/dashboard.php',
    'public/booking.php', 'api/booking_api.php', 'notifications/send_confirmation.php',
    'notifications/send_otp.php', 'notifications/verify_otp.php'
];
foreach ($required as $f) {
    if (file_exists(__DIR__ . '/' . $f)) {
        $status[] = "✅ $f found";
    } else {
        $errors[] = "❌ $f is MISSING from the server folder";
    }
}

// ── 10. Show all pending appointments ─────────────
$pending = [];
$res = $conn->query("SELECT id, patient_name, email, appointment_datetime, booking_ref, payment_ref, payment_method, status, created_at FROM appointments ORDER BY created_at DESC LIMIT 20");
if ($res) {
    while ($r = $res->fetch_assoc()) $pending[] = $r;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Clinic System Setup & Diagnostics</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; max-width: 800px; margin: 40px auto; padding: 0 24px; color: #1a1a2e; background: #f7f5f2; }
  h1 { font-size: 1.6rem; margin-bottom: 4px; }
  h2 { font-size: 1.1rem; margin: 28px 0 10px; border-bottom: 2px solid #e8e3dc; padding-bottom: 6px; }
  .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: .8rem; font-weight: 700; }
  .ok { background: #e8f5ee; color: #2d8a6b; }
  .err { background: #fdf0ef; color: #c0392b; }
  .warn { background: #fef7e6; color: #c6860a; }
  .status-list { list-style: none; padding: 0; }
  .status-list li { padding: 5px 0; font-size: .9rem; border-bottom: 1px solid #f0ebe4; }
  table { width: 100%; border-collapse: collapse; font-size: .82rem; margin-top: 8px; }
  th { background: #4f2d8a; color: #fff; padding: 8px 10px; text-align: left; }
  td { padding: 8px 10px; border-bottom: 1px solid #e8e3dc; }
  tr:hover td { background: #faf7f2; }
  .card { background: #fff; border-radius: 12px; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,.07); margin-bottom: 20px; }
  .delete-warn { background: #fef7e6; border: 1.5px solid #f5c97a; border-radius: 10px; padding: 14px 18px; margin-top: 20px; font-size: .88rem; }
  code { background: #f0ebe4; padding: 2px 6px; border-radius: 4px; font-size: .85em; }
  .status-pending   { background: #fff5e6; color: #c6860a; }
  .status-confirmed { background: #e8f5ee; color: #2d8a6b; }
  .status-rejected  { background: #fdf0ef; color: #c0392b; }
  .status-cancelled { background: #f3f0f7; color: #6b6b7e; }
</style>
</head>
<body>

<div class="card">
  <h1>🏥 Clinic System — Setup & Diagnostics</h1>
  <p style="color:#6b6b7e;font-size:.88rem">This page checks your server setup and creates missing database tables.</p>
</div>

<?php if ($errors): ?>
<div class="card">
  <h2>⚠️ Issues Found</h2>
  <ul class="status-list">
    <?php foreach ($errors as $e): ?>
      <li><span class="badge err"><?= $e ?></span></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<div class="card">
  <h2>✅ Setup Status</h2>
  <ul class="status-list">
    <?php foreach ($status as $s): ?>
      <li><?= $s ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="card">
  <h2>📊 Database Contents</h2>
  <ul class="status-list">
    <?php foreach ($counts as $tbl => $n): ?>
      <li><strong><?= $tbl ?></strong> — <?= $n ?> row(s)</li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="card">
  <h2>📋 All Appointments (latest 20)</h2>
  <?php if (empty($pending)): ?>
    <p style="color:#6b6b7e;font-size:.88rem">No appointments found in the database yet.</p>
    <p style="font-size:.82rem;color:#c0392b">
      If a patient just submitted a booking and it's not here, the problem is in <code>send_confirmation.php</code>.
      Check your PHP error log for INSERT errors.
    </p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Email</th><th>Date &amp; Time</th>
          <th>Ref #</th><th>Payment Ref</th><th>Method</th><th>Status</th><th>Submitted</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pending as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['patient_name']) ?></td>
          <td><?= htmlspecialchars($a['email']) ?></td>
          <td><?= htmlspecialchars($a['appointment_datetime']) ?></td>
          <td><code><?= htmlspecialchars($a['booking_ref']) ?></code></td>
          <td><code><?= htmlspecialchars($a['payment_ref']) ?></code></td>
          <td><?= strtoupper(htmlspecialchars($a['payment_method'])) ?></td>
          <td><span class="badge status-<?= $a['status'] ?>"><?= $a['status'] ?></span></td>
          <td><?= $a['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<div class="delete-warn">
  ⚠️ <strong>Security reminder:</strong> Delete <code>setup.php</code> from your server after running it. 
  It exposes your database structure and patient data without authentication.
</div>

</body>
</html>