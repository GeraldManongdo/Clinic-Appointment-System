<?php
$path = trim($_GET['route'] ?? 'home', '/');
$segments = explode('/', $path);
$controller = $segments[0] ?: 'home';
$action = $segments[1] ?? null;

require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AppointmentController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ContactController.php';
require_once __DIR__ . '/../app/controllers/AjaxController.php';

switch ($controller) {
    case 'appointment':
        $controller = new AppointmentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'book') {
            $controller->book();
        } else {
            $controller->index();
        }
        break;
    case 'profile':
        $controller = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            $controller->index();
        }
        break;
    case 'auth':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handle();
        } else {
            if (($_GET['action'] ?? '') === 'logout') {
                Auth::logout();
                header('Location: ' . APP_URL);
                return;
            }
            $controller->show();
        }
        break;
    case 'contact':
        $controller = new ContactController();
        $controller->send();
        break;
    case 'ajax':
        $controller = new AjaxController();
        $controller->handle();
        break;
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
