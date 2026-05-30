<?php
class AdminAjaxController extends Controller
{
    public function handle(): void
    {
        $action = $_POST['action'] ?? '';
        header('Content-Type: application/json');
        switch ($action) {
            case 'update_appointment_status':
                $this->updateAppointmentStatus();
                break;
            case 'toggle_service':
                $this->toggleService();
                break;
            case 'save_service':
                $this->saveService();
                break;
            case 'save_user':
                $this->saveUser();
                break;
            case 'save_setting':
                $this->saveSetting();
                break;
            case 'block_date':
                $this->blockDate();
                break;
            default:
                echo json_encode(['success' => false]);
        }
    }

    private function updateAppointmentStatus(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $notes = trim($_POST['notes'] ?? '');
        $result = (new AppointmentModel())->updateStatus($id, $status, $notes);
        echo json_encode(['success' => $result]);
    }

    private function toggleService(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $visible = (int)($_POST['visible'] ?? 0);
        $result = (new ServiceModel())->toggle($id, $visible);
        echo json_encode(['success' => $result]);
    }

    private function saveService(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $visible = isset($_POST['visible']) ? 1 : 0;
        $imagePath = '';
        if (!empty($_FILES['image']['tmp_name'])) {
            $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . '/services/' . $name);
            $imagePath = 'services/' . $name;
        }

        if ($id) {
            $service = (new ServiceModel())->find($id);
            if ($service && !$imagePath) {
                $imagePath = $service['image_path'];
            }
            $result = (new ServiceModel())->update($id, ['title' => $title, 'description' => $description, 'image_path' => $imagePath, 'visible' => $visible]);
        } else {
            $result = (new ServiceModel())->create(['title' => $title, 'description' => $description, 'image_path' => $imagePath, 'visible' => $visible]);
        }
        echo json_encode(['success' => (bool)$result]);
    }

    private function saveSetting(): void
    {
        $model = new SiteSettingModel();
        $updated = false;
        foreach ($_POST as $key => $value) {
            if ($key === 'action' || $key === 'csrf_token') {
                continue;
            }
            $updated = $model->update($key, trim($value)) || $updated;
        }
        echo json_encode(['success' => $updated]);
    }

    private function saveUser(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $phone = trim($_POST['phone'] ?? '');
        $role = in_array($_POST['role'] ?? 'user', ['user', 'admin']) ? $_POST['role'] : 'user';
        $password = trim($_POST['password'] ?? '');

        if (!$name || !$email) {
            echo json_encode(['success' => false]);
            return;
        }

        $db = Database::connect();
        if ($id) {
            $query = 'UPDATE users SET name = ?, email = ?, phone = ?, role = ?';
            $params = [$name, $email, $phone, $role];
            if ($password) {
                $query .= ', password = ?';
                $params[] = password_hash($password, PASSWORD_DEFAULT);
            }
            $query .= ' WHERE id = ?';
            $params[] = $id;
            $stmt = $db->prepare($query);
            $result = $stmt->execute($params);
            echo json_encode(['success' => $result]);
            return;
        }

        $existing = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $existing->execute([$email]);
        if ($existing->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }

        $stmt = $db->prepare('INSERT INTO users (name, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $result = $stmt->execute([$name, $email, $phone, password_hash($password ?: bin2hex(random_bytes(8)), PASSWORD_DEFAULT), $role]);
        echo json_encode(['success' => $result]);
    }

    private function blockDate(): void
    {
        $date = $_POST['date'] ?? '';
        if (!$date) {
            echo json_encode(['success' => false]);
            return;
        }
        $stmt = Database::connect()->prepare('INSERT INTO blocked_dates (date_value, created_at) VALUES (?, NOW())');
        $result = $stmt->execute([$date]);
        echo json_encode(['success' => $result]);
    }
}
