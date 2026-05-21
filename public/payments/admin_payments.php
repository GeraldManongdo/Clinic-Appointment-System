<?php
/**
 * admin_payments.php
 * Backend API for the Payment References Admin Panel
 *
 * GET  ?action=list                         → returns all refs as JSON
 * POST action=add    method/ref/sender/amount → add new reference
 * POST action=delete id                       → delete unused reference
 *
 * ⚠️  PROTECT THIS FILE IN PRODUCTION:
 *     Add session/login check before allowing access.
 *     Example: if (!isset($_SESSION['admin'])) { http_response_code(403); exit; }
 */

// ── TODO: UNCOMMENT AND ADD YOUR ADMIN LOGIN CHECK ──
// session_start();
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     http_response_code(403);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: same-origin');

// ── DB Connect ────────────────────────────────────
$conn = new mysqli("localhost", "root", "", "clinic");
if ($conn->connect_error) {
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

// ── Auto-create Table ─────────────────────────────
$conn->query("
    CREATE TABLE IF NOT EXISTS payment_references (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        payment_method   ENUM('gcash','maya') NOT NULL,
        reference_number VARCHAR(100)         NOT NULL UNIQUE,
        sender_name      VARCHAR(200)         NOT NULL,
        amount           DECIMAL(10,2)        NOT NULL DEFAULT 500.00,
        is_used          TINYINT(1)           NOT NULL DEFAULT 0,
        used_at          DATETIME             NULL,
        used_by_ip       VARCHAR(45)          NULL,
        notes            TEXT                 NULL,
        created_at       TIMESTAMP            DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ref    (reference_number),
        INDEX idx_method (payment_method),
        INDEX idx_used   (is_used)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// ══════════════════════════════════════════════════
// LIST — GET all references + stats
// ══════════════════════════════════════════════════
if ($action === 'list') {

    $refs = [];
    $result = $conn->query("
        SELECT id, payment_method, reference_number, sender_name,
               amount, is_used,
               DATE_FORMAT(used_at,   '%b %d %Y %h:%i %p') AS used_at,
               DATE_FORMAT(created_at,'%b %d %Y %h:%i %p') AS created_at
        FROM payment_references
        ORDER BY created_at DESC
    ");
    while ($row = $result->fetch_assoc()) {
        $refs[] = $row;
    }

    // Stats
    $stats = $conn->query("
        SELECT
            COUNT(*)                              AS total,
            SUM(is_used = 0)                      AS unused,
            SUM(is_used = 1)                      AS used,
            SUM(CASE WHEN is_used=0 THEN amount ELSE 0 END) AS pending_amount,
            SUM(amount)                           AS total_amount
        FROM payment_references
    ")->fetch_assoc();

    echo json_encode(['refs' => $refs, 'stats' => $stats]);
    $conn->close();
    exit;
}

// ══════════════════════════════════════════════════
// ADD — Insert new payment reference
// ══════════════════════════════════════════════════
if ($action === 'add') {

    $method = strtolower(trim($_POST['method'] ?? ''));
    $ref    = strtoupper(preg_replace('/[\s\-]/', '', trim($_POST['ref'] ?? '')));
    $sender = trim($_POST['sender'] ?? '');
    $amount = floatval($_POST['amount'] ?? 500);
    $notes  = trim($_POST['notes'] ?? '');

    // Validate
    if (empty($ref) || empty($sender) || empty($method)) {
        echo json_encode(['status' => 'ERROR', 'message' => 'Missing required fields']);
        $conn->close(); exit;
    }
    if (!in_array($method, ['gcash', 'maya'])) {
        echo json_encode(['status' => 'ERROR', 'message' => 'Invalid payment method']);
        $conn->close(); exit;
    }
    if ($amount <= 0) {
        echo json_encode(['status' => 'ERROR', 'message' => 'Invalid amount']);
        $conn->close(); exit;
    }

    // Check duplicate
    $check = $conn->prepare("SELECT id FROM payment_references WHERE reference_number = ?");
    $check->bind_param("s", $ref);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'DUPLICATE', 'message' => 'Reference already exists']);
        $check->close(); $conn->close(); exit;
    }
    $check->close();

    // Insert
    $stmt = $conn->prepare("
        INSERT INTO payment_references (payment_method, reference_number, sender_name, amount, notes)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssds", $method, $ref, $sender, $amount, $notes);

    if ($stmt->execute()) {
        error_log("admin_payments - Added ref: [{$method}] {$ref} | {$sender} | ₱{$amount}");
        echo json_encode(['status' => 'ADDED', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['status' => 'ERROR', 'message' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ══════════════════════════════════════════════════
// DELETE — Remove an unused reference
// ══════════════════════════════════════════════════
if ($action === 'delete') {

    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['status' => 'ERROR', 'message' => 'Invalid ID']);
        $conn->close(); exit;
    }

    // Only allow deleting UNUSED references
    $stmt = $conn->prepare("DELETE FROM payment_references WHERE id = ? AND is_used = 0");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'DELETED']);
    } else {
        echo json_encode(['status' => 'ERROR', 'message' => 'Could not delete (already used or not found)']);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Unknown action
echo json_encode(['error' => 'Unknown action']);
$conn->close();
?>