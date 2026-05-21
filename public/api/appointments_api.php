<?php
/**
 * appointments_api.php
 * Admin API for managing appointments
 */

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../includes/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'success' => false]);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// ═════════════════════════════════════════════════════════════
// ── GET LIST OF APPOINTMENTS ──────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'list' && $method === 'GET') {
    $status = $_GET['status'] ?? '';
    $date = $_GET['date'] ?? '';
    $search = $_GET['search'] ?? '';

    $query = "SELECT * FROM appointments WHERE 1=1";
    $params = [];
    $types = '';

    if ($status) {
        $query .= " AND status = ?";
        $params[] = $status;
        $types .= 's';
    }

    if ($date) {
        $query .= " AND appointment_date = ?";
        $params[] = $date;
        $types .= 's';
    }

    if ($search) {
        $query .= " AND (patient_name LIKE ? OR email LIKE ?)";
        $searchTerm = '%' . $search . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }

    $query .= " ORDER BY appointment_date DESC, appointment_time DESC LIMIT 500";

    if ($params) {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['error' => 'Query error: ' . $conn->error, 'success' => false]);
            $conn->close();
            exit;
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($query);
    }

    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    if (isset($stmt)) $stmt->close();

    echo json_encode([
        'success' => true,
        'appointments' => $appointments,
        'count' => count($appointments)
    ]);
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── GET SINGLE APPOINTMENT ────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'get' && $method === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Appointment ID required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();

    if ($appointment) {
        echo json_encode(['success' => true, 'appointment' => $appointment]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Appointment not found', 'success' => false]);
    }
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── POST UPDATE APPOINTMENT STATUS ────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'update_status' && $method === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    if (!$id || !in_array($status, ['pending', 'confirmed', 'rejected', 'cancelled'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Valid appointment ID and status required', 'success' => false]);
        $conn->close();
        exit;
    }

    $confirmed_at = ($status === 'confirmed') ? date('Y-m-d H:i:s') : null;

    if ($reason) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ?, cancel_reason = ?, confirmed_at = ? WHERE id = ?");
        $stmt->bind_param("sssi", $status, $reason, $confirmed_at, $id);
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET status = ?, confirmed_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $confirmed_at, $id);
    }

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Appointment status updated to ' . $status
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update appointment', 'success' => false]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── POST UPDATE APPOINTMENT NOTES ─────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'update_notes' && $method === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Appointment ID required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("UPDATE appointments SET admin_notes = ? WHERE id = ?");
    $stmt->bind_param("si", $notes, $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Notes updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update notes', 'success' => false]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── DEFAULT ERROR ─────────────────────────────────────────────
// ═════════════════════════════════════════════════════════════
http_response_code(400);
echo json_encode(['error' => 'Invalid action or method', 'success' => false]);
$conn->close();
?>
