<?php
/**
 * services_api.php
 * Admin API for managing services
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

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS site_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    service_description TEXT,
    service_price DECIMAL(10, 2) NOT NULL,
    service_category VARCHAR(100),
    service_icon VARCHAR(100),
    service_color VARCHAR(50) DEFAULT 'primary',
    active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (active),
    INDEX idx_category (service_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// ═════════════════════════════════════════════════════════════
// ── GET LIST OF SERVICES ──────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'list' && $method === 'GET') {
    $result = $conn->query("SELECT * FROM site_services WHERE active = 1 ORDER BY service_name ASC");
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    echo json_encode([
        'success' => true,
        'services' => $services
    ]);
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── GET SINGLE SERVICE ────────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'get' && $method === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Service ID required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM site_services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    if ($service) {
        echo json_encode(['success' => true, 'service' => $service]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Service not found', 'success' => false]);
    }
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── POST ADD SERVICE ──────────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'add' && $method === 'POST') {
    $name = trim($_POST['service_name'] ?? '');
    $desc = trim($_POST['service_description'] ?? '');
    $price = floatval($_POST['service_price'] ?? 0);
    $category = trim($_POST['service_category'] ?? 'general');
    $icon = trim($_POST['service_icon'] ?? 'bi-hospital');
    $color = trim($_POST['service_color'] ?? 'primary');

    if (!$name || $price <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Service name and valid price required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO site_services (service_name, service_description, service_price, service_category, service_icon, service_color) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $desc, $price, $category, $icon, $color);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Service added successfully!',
            'id' => $conn->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add service', 'success' => false]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── POST UPDATE SERVICE ───────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'update' && $method === 'POST') {
    $id = intval($_POST['service_id'] ?? 0);
    $name = trim($_POST['service_name'] ?? '');
    $desc = trim($_POST['service_description'] ?? '');
    $price = floatval($_POST['service_price'] ?? 0);
    $category = trim($_POST['service_category'] ?? 'general');
    $icon = trim($_POST['service_icon'] ?? 'bi-hospital');
    $color = trim($_POST['service_color'] ?? 'primary');

    if (!$id || !$name || $price <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Service ID, name, and valid price required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("UPDATE site_services SET service_name = ?, service_description = ?, service_price = ?, service_category = ?, service_icon = ?, service_color = ? WHERE id = ?");
    $stmt->bind_param("ssdsssi", $name, $desc, $price, $category, $icon, $color, $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Service updated successfully!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update service', 'success' => false]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ═════════════════════════════════════════════════════════════
// ── POST DELETE SERVICE ───────────────────────────────────────
// ═════════════════════════════════════════════════════════════
if ($action === 'delete' && $method === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Service ID required', 'success' => false]);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("UPDATE site_services SET active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Service deleted successfully!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete service', 'success' => false]);
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
