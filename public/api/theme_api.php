<?php
/**
 * theme_api.php
 * API for managing site theme, colors, and fonts
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
// READ ALL THEME SETTINGS
// ============================================================
if ($action === 'read_all') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_theme ORDER BY theme_type, theme_key");
        $stmt->execute();
        $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grouped = [];
        foreach ($themes as $theme) {
            if (!isset($grouped[$theme['theme_type']])) {
                $grouped[$theme['theme_type']] = [];
            }
            $grouped[$theme['theme_type']][] = $theme;
        }
        
        $response = ['status' => 'success', 'data' => $grouped];
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// READ THEME BY TYPE
// ============================================================
elseif ($action === 'read_by_type') {
    try {
        $type = $_GET['type'] ?? null;
        if (!$type) throw new Exception('Theme type required');
        
        $stmt = $pdo->prepare("SELECT * FROM site_theme WHERE theme_type = ? ORDER BY theme_key");
        $stmt->execute([$type]);
        $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = ['status' => 'success', 'data' => $themes];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// UPDATE THEME SETTING
// ============================================================
elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $key = $_POST['theme_key'] ?? null;
        if (!$key) throw new Exception('Theme key required');
        
        $value = $_POST['theme_value'] ?? null;
        if ($value === null) throw new Exception('Theme value required');
        
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM site_theme WHERE theme_key = ?");
        $stmt->execute([$key]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            $stmt = $pdo->prepare("UPDATE site_theme SET theme_value = ? WHERE theme_key = ?");
            $stmt->execute([$value, $key]);
        } else {
            $type = $_POST['theme_type'] ?? 'other';
            $stmt = $pdo->prepare("INSERT INTO site_theme (theme_key, theme_value, theme_type) VALUES (?, ?, ?)");
            $stmt->execute([$key, $value, $type]);
        }
        
        $response = ['status' => 'success', 'message' => 'Theme setting updated successfully'];
    } catch (Exception $e) {
        http_response_code(400);
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

// ============================================================
// GENERATE CSS FROM THEME
// ============================================================
elseif ($action === 'generate_css') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_theme");
        $stmt->execute();
        $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $css = ":root {\n";
        foreach ($themes as $theme) {
            if ($theme['theme_type'] === 'color') {
                $css .= "  --" . str_replace('_', '-', $theme['theme_key']) . ": " . $theme['theme_value'] . ";\n";
            } elseif ($theme['theme_type'] === 'font') {
                $css .= "  --" . str_replace('_', '-', $theme['theme_key']) . ": " . $theme['theme_value'] . ";\n";
            }
        }
        $css .= "}\n";
        
        header('Content-Type: text/css');
        echo $css;
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}

echo json_encode($response);
?>
