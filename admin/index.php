<?php
session_start();
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/core/Database.php';
require __DIR__ . '/../app/core/Model.php';
require __DIR__ . '/../app/core/Controller.php';
require __DIR__ . '/../app/core/Auth.php';

spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/../app/controllers', __DIR__ . '/../app/models', __DIR__ . '/../app/core'];
    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$action = $_GET['route'] ?? 'dashboard';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $user = $email ? (new UserModel())->findByEmail($email) : null;
    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
        Auth::login($user);
        header('Location: index.php');
        exit;
    }
}

if (($action !== 'login' && !Auth::admin())) {
    header('Location: index.php?route=login');
    exit;
}

$controller = new AdminController();
switch ($action) {
    case 'appointments':
        $controller->appointments();
        break;
    case 'services':
        $controller->services();
        break;
    case 'schedule':
        $controller->schedule();
        break;
    case 'content':
        $controller->content();
        break;
    case 'messages':
        $controller->messages();
        break;
    case 'settings':
        $controller->settings();
        break;
    case 'accounts':
        $controller->accounts();
        break;
    case 'logout':
        Auth::logout();
        header('Location: index.php?route=login');
        break;
    case 'ajax':
        (new AdminAjaxController())->handle();
        break;
    case 'login':
        require __DIR__ . '/../app/views/admin/login.php';
        break;
    default:
        $controller->dashboard();
        break;
}
