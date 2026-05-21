<?php
/**
 * booking_api.php
 * Public API for the booking page — NO auth required
 *
 * Actions:
 *   GET blocked_dates   → dates that are blocked or fully booked (for calendar)
 *   GET available_slots → available time slots for a given date
 *   POST booking        → save new appointment
 */

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once '../../includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

// Auto-create tables if not yet there
$conn->query("CREATE TABLE IF NOT EXISTS blocked_dates (id INT AUTO_INCREMENT PRIMARY KEY, block_date DATE NOT NULL UNIQUE, reason VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX idx_date (block_date)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$conn->query("CREATE TABLE IF NOT EXISTS blocked_time_slots (id INT AUTO_INCREMENT PRIMARY KEY, block_date DATE NOT NULL, slot_time VARCHAR(30) NOT NULL, reason VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY unique_slot (block_date, slot_time)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$conn->query("CREATE TABLE IF NOT EXISTS appointments (id INT AUTO_INCREMENT PRIMARY KEY, patient_name VARCHAR(200) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50), service_id INT, doctor_id INT, appointment_date DATE NOT NULL, appointment_time VARCHAR(20) NOT NULL, appointment_datetime VARCHAR(100) NOT NULL, payment_method ENUM('gcash','maya','cash') DEFAULT 'cash', payment_ref VARCHAR(100), receipt_image VARCHAR(255), sender_name VARCHAR(200), booking_ref VARCHAR(50) UNIQUE, amount DECIMAL(10,2) DEFAULT 500.00, status ENUM('pending','confirmed','rejected','cancelled') DEFAULT 'pending', admin_notes TEXT, cancel_reason TEXT, dob DATE, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, confirmed_at TIMESTAMP NULL, INDEX idx_status (status), INDEX idx_date (appointment_date), INDEX idx_email (email)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// All possible time slots
$ALL_SLOTS = ['8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM'];

// ─── POST SAVE APPOINTMENT ───────────────────────
if ($method === 'POST' && $_POST) {
    $patient_name = trim($_POST['patient_name'] ?? '');
    $patient_email = trim($_POST['patient_email'] ?? '');
    $patient_phone = trim($_POST['patient_phone'] ?? '');
    $patient_dob = $_POST['patient_dob'] ?? '';
    $service_id = $_POST['service_id'] ?? null;
    $doctor_id = $_POST['doctor_id'] ?? null;
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $notes = trim($_POST['notes'] ?? '');

    // Validate required fields
    if (!$patient_name || !$patient_email || !$patient_phone || !$appointment_date || !$appointment_time) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields', 'success' => false]);
        $conn->close();
        exit;
    }

    // Validate email format
    if (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format', 'success' => false]);
        $conn->close();
        exit;
    }

    // Generate booking reference
    $booking_ref = 'BK' . date('YmdHis') . rand(1000, 9999);
    $appointment_datetime = $appointment_date . ' ' . $appointment_time;

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO appointments (patient_name, email, phone, dob, service_id, doctor_id, appointment_date, appointment_time, appointment_datetime, booking_ref, status, admin_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)");

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $conn->error, 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt->bind_param("ssssiisssss", $patient_name, $patient_email, $patient_phone, $patient_dob, $service_id, $doctor_id, $appointment_date, $appointment_time, $appointment_datetime, $booking_ref, $notes);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Appointment booked successfully! Check your email for confirmation.',
            'booking_ref' => $booking_ref,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save appointment: ' . $stmt->error, 'success' => false]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// ── GET BLOCKED DATES (for calendar) ──────────────────────
if ($action === 'blocked_dates') {
    $blocked = [];
    $res = $conn->query("SELECT block_date FROM blocked_dates WHERE block_date >= CURDATE() ORDER BY block_date ASC");
    while ($r = $res->fetch_assoc()) $blocked[] = $r['block_date'];

    $slot_count = count($ALL_SLOTS);
    $res2 = $conn->query("SELECT appointment_date, COUNT(*) AS cnt FROM appointments WHERE status='confirmed' AND appointment_date >= CURDATE() GROUP BY appointment_date HAVING cnt >= {$slot_count}");
    $full = [];
    while ($r = $res2->fetch_assoc()) $full[] = $r['appointment_date'];

    echo json_encode(['blocked_dates' => $blocked, 'fully_booked' => $full]);
    $conn->close(); exit;
}

// ── GET AVAILABLE SLOTS FOR A DATE ───────────────────────
if ($action === 'available_slots') {
    $date = $_GET['date'] ?? '';
    if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        echo json_encode(['error' => 'Valid date required (YYYY-MM-DD)']);
        $conn->close(); exit;
    }

    // Check if entire date is blocked
    $stmt = $conn->prepare("SELECT reason FROM blocked_dates WHERE block_date = ? LIMIT 1");
    $stmt->bind_param("s", $date); $stmt->execute();
    $blocked_row = $stmt->get_result()->fetch_assoc(); $stmt->close();

    if ($blocked_row) {
        echo json_encode(['blocked' => true, 'block_reason' => $blocked_row['reason'] ?: 'Clinic closed on this date', 'available_slots' => []]);
        $conn->close(); exit;
    }

    // Confirmed-booked slots
    $stmt = $conn->prepare("SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status = 'confirmed'");
    $stmt->bind_param("s", $date); $stmt->execute();
    $res = $stmt->get_result(); $booked = [];
    while ($r = $res->fetch_assoc()) $booked[] = $r['appointment_time'];
    $stmt->close();

    // Admin-blocked individual time slots
    $stmt2 = $conn->prepare("SELECT slot_time FROM blocked_time_slots WHERE block_date = ?");
    $stmt2->bind_param("s", $date); $stmt2->execute();
    $res2 = $stmt2->get_result(); $blocked_slots = [];
    while ($r = $res2->fetch_assoc()) $blocked_slots[] = $r['slot_time'];
    $stmt2->close();

    $unavailable = array_unique(array_merge($booked, $blocked_slots));
    $available   = array_values(array_diff($ALL_SLOTS, $unavailable));

    echo json_encode(['blocked' => false, 'available_slots' => $available, 'booked_slots' => $booked, 'blocked_slots' => $blocked_slots]);
    $conn->close(); exit;
}

echo json_encode(['error' => 'Invalid action or method']);
$conn->close();
?>