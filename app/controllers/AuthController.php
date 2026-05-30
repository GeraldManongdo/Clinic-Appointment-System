<?php
class AuthController extends Controller
{
    public function show(): void
    {
        $action = $_GET['action'] ?? 'login';
        if ($action === 'register') {
            $this->render('auth/register', ['site' => (new SiteSettingModel())->all()]);
            return;
        }
        $this->render('auth/login', ['site' => (new SiteSettingModel())->all()]);
    }

    public function handle(): void
    {
        $action = $_POST['action'] ?? '';
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $userModel = new UserModel();

        if ($action === 'login') {
            $user = $email ? $userModel->findByEmail($email) : null;
            if ($user && password_verify($password, $user['password'])) {
                Auth::login($user);
                header('Location: ' . APP_URL);
                return;
            }
            header('Location: ' . APP_URL . '/?route=auth&action=login&error=1');
            return;
        }

        if ($action === 'register') {
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            if (!$email || !$password || !$name) {
                header('Location: ' . APP_URL . '/?route=auth&action=register&error=1');
                return;
            }
            if ($userModel->findByEmail($email)) {
                header('Location: ' . APP_URL . '/?route=auth&action=register&error=2');
                return;
            }
            $id = $userModel->create(['name' => $name, 'email' => $email, 'phone' => $phone, 'password' => $password, 'role' => 'user']);
            $user = $userModel->findById($id);
            Auth::login($user);
            header('Location: ' . APP_URL . '/?route=appointment');
            return;
        }

        if ($action === 'logout') {
            Auth::logout();
            header('Location: ' . APP_URL);
            return;
        }

        header('Location: ' . APP_URL);
    }
}
