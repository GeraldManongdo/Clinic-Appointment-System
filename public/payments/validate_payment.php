<?php
/**
 * validate_payment.php — Whitelist-based payment validation
 * ═══════════════════════════════════════════════════════════════
 * HOW IT WORKS:
 *   1. You (admin) add real reference numbers in admin_payments.php
 *      right after receiving payment in GCash or Maya
 *   2. Patient enters that reference during booking
 *   3. This checks DB → valid & unused → marks it USED forever
 *   4. Same reference can NEVER be used again
 *
 * Returns:
 *   VALID|<sender_name>  — Approved, now marked used
 *   INVALID              — Not found in your DB
 *   ALREADY_USED         — Was already used in a previous booking
 *   WRONG_METHOD         — Ref exists but for different payment method
 *   WRONG_AMOUNT         — Amount doesn't match
 *   ERROR                — Server/DB error
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: text/plain');

if (empty($_POST['method']) || empty($_POST['ref']) || empty($_POST['amount'])) {
    echo "ERROR"; exit;
}

$method = strtolower(trim($_POST['method']));
$ref    = strtoupper(preg_replace('/[\s\-]/', '', trim($_POST['ref'])));
$amount = floatval($_POST['amount']);

if (!in_array($method, ['gcash', 'maya'])) { echo "ERROR"; exit; }

$conn = new mysqli("localhost", "root", "", "clinic");
if ($conn->connect_error) { error_log("DB error: " . $conn->connect_error); echo "ERROR"; exit; }

// Look up the reference
$stmt = $conn->prepare(
    "SELECT id, payment_method, amount, sender_name, is_used, used_at
     FROM payment_references WHERE reference_number = ? LIMIT 1"
);
if (!$stmt) { echo "ERROR"; $conn->close(); exit; }

$stmt->bind_param("s", $ref);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    error_log("validate_payment - Not found: {$ref}");
    echo "INVALID";
    $stmt->close(); $conn->close(); exit;
}

$row = $res->fetch_assoc();
$stmt->close();

// Already used?
if ((int)$row['is_used'] === 1) {
    error_log("validate_payment - Already used: {$ref} at {$row['used_at']}");
    echo "ALREADY_USED";
    $conn->close(); exit;
}

// Wrong payment method?
if ($row['payment_method'] !== $method) {
    error_log("validate_payment - Wrong method: expected {$row['payment_method']}, got {$method}");
    echo "WRONG_METHOD";
    $conn->close(); exit;
}

// Wrong amount?
if (abs(floatval($row['amount']) - $amount) > 0.01) {
    error_log("validate_payment - Wrong amount: expected {$row['amount']}, got {$amount}");
    echo "WRONG_AMOUNT";
    $conn->close(); exit;
}

// ✅ Mark as USED — can never be used again
$ip  = $conn->real_escape_string($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$upd = $conn->prepare(
    "UPDATE payment_references SET is_used=1, used_at=NOW(), used_by_ip=? WHERE id=?"
);
$upd->bind_param("si", $ip, $row['id']);
if (!$upd->execute()) {
    error_log("validate_payment - Update failed: " . $upd->error);
    echo "ERROR";
    $upd->close(); $conn->close(); exit;
}
$upd->close();

$senderName = !empty($row['sender_name']) ? $row['sender_name'] : 'Unknown Sender';
error_log("validate_payment - ✓ VALID & MARKED USED: [{$method}] {$ref} | Sender: {$senderName}");

$conn->close();
echo "VALID|" . $senderName;
?>