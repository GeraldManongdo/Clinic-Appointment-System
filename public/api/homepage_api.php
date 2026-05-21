<?php
/**
 * homepage_api.php
 * API for managing homepage content (hero, sections, services)
 */
require_once '../../includes/config.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$action = $_GET['action'] ?? ($_POST['action'] ?? 'read');
$response = ['status' => 'error', 'message' => 'Invalid action'];

// ============================================================
// HERO SECTION CRUD
// ============================================================
if ($action === 'read_hero') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_hero WHERE id = 1");
        $stmt->execute();
        $hero = $stmt->fetch(PDO::FETCH_ASSOC);
        $response = ['status' => 'success', 'data' => $hero ?? []];
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'update_hero' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fields = ['hero_pill_text', 'hero_title', 'hero_subtitle', 'cta_button_text', 'cta_button_link', 'secondary_button_text', 'secondary_button_link', 'stat1_number', 'stat1_label', 'stat2_number', 'stat2_label'];
        $updates = [];
        $params = [];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $updates[] = "`$field` = ?";
                $params[] = $_POST[$field];
            }
        }
        
        if (empty($updates)) {
            throw new Exception('No fields to update');
        }
        
        $params[] = 1;
        $sql = "UPDATE site_hero SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $response = ['status' => 'success', 'message' => 'Hero section updated successfully'];
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// WHY CHOOSE US SECTION CRUD
// ============================================================
elseif ($action === 'read_features') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_features WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6");
        $stmt->execute();
        $features = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = ['status' => 'success', 'data' => $features];
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'add_feature' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check count
        $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM site_features WHERE is_active = 1");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        
        if ($count >= 6) {
            throw new Exception('Maximum 6 features allowed. Please delete an existing feature first.');
        }
        
        $stmt = $pdo->prepare("INSERT INTO site_features (icon, title, description, sort_order, is_active) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([
            $_POST['icon'] ?? '⭐',
            $_POST['title'] ?? '',
            $_POST['description'] ?? '',
            $_POST['sort_order'] ?? 0
        ]);
        
        $response = ['status' => 'success', 'message' => 'Feature added successfully', 'id' => $pdo->lastInsertId()];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'update_feature' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('Feature ID required');
        
        $stmt = $pdo->prepare("UPDATE site_features SET icon = ?, title = ?, description = ?, sort_order = ? WHERE id = ?");
        $stmt->execute([
            $_POST['icon'] ?? '⭐',
            $_POST['title'] ?? '',
            $_POST['description'] ?? '',
            $_POST['sort_order'] ?? 0,
            $id
        ]);
        
        $response = ['status' => 'success', 'message' => 'Feature updated successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'delete_feature' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('Feature ID required');
        
        $stmt = $pdo->prepare("UPDATE site_features SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        $response = ['status' => 'success', 'message' => 'Feature deleted successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// SERVICES CRUD
// ============================================================
elseif ($action === 'read_services') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_services WHERE is_active = 1 ORDER BY sort_order ASC");
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = ['status' => 'success', 'data' => $services];
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'add_service' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO site_services (icon, badge, title, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $_POST['icon'] ?? '🏥',
            $_POST['badge'] ?? 'General',
            $_POST['title'] ?? '',
            $_POST['description'] ?? '',
            $_POST['sort_order'] ?? 0
        ]);
        
        $response = ['status' => 'success', 'message' => 'Service added successfully', 'id' => $pdo->lastInsertId()];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'update_service' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('Service ID required');
        
        $stmt = $pdo->prepare("UPDATE site_services SET icon = ?, badge = ?, title = ?, description = ?, sort_order = ? WHERE id = ?");
        $stmt->execute([
            $_POST['icon'] ?? '🏥',
            $_POST['badge'] ?? 'General',
            $_POST['title'] ?? '',
            $_POST['description'] ?? '',
            $_POST['sort_order'] ?? 0,
            $id
        ]);
        
        $response = ['status' => 'success', 'message' => 'Service updated successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'delete_service' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('Service ID required');
        
        $stmt = $pdo->prepare("UPDATE site_services SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        $response = ['status' => 'success', 'message' => 'Service deleted successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// SECTIONS (Generic - Ready to Book, etc.)
// ============================================================
elseif ($action === 'read_section') {
    try {
        $key = $_GET['key'] ?? null;
        if (!$key) throw new Exception('Section key required');
        
        $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = ?");
        $stmt->execute([$key]);
        $section = $stmt->fetch(PDO::FETCH_ASSOC);
        $response = ['status' => 'success', 'data' => $section ?? []];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

elseif ($action === 'update_section' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $key = $_POST['section_key'] ?? null;
        if (!$key) throw new Exception('Section key required');
        
        $fields = ['title', 'subtitle', 'description', 'tagline'];
        $updates = [];
        $params = [];
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $updates[] = "`$field` = ?";
                $params[] = $_POST[$field];
            }
        }
        
        if (empty($updates)) {
            throw new Exception('No fields to update');
        }
        
        $params[] = $key;
        $sql = "UPDATE site_sections SET " . implode(', ', $updates) . " WHERE section_key = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $response = ['status' => 'success', 'message' => 'Section updated successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

echo json_encode($response);
?>
