<?php
class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            header('Location: ' . APP_URL . '/?route=appointment');
            exit;
        }

        $user = Auth::user();
        $appointments = (new AppointmentModel())->findByUser($user['id']);
        $this->render('profile', ['user' => $user, 'appointments' => $appointments, 'site' => (new SiteSettingModel())->all()]);
    }

    public function update(): void
    {
        if (!Auth::check()) {
            header('Location: ' . APP_URL);
            exit;
        }
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $user = Auth::user();
        (new UserModel())->update($user['id'], ['name' => $name, 'phone' => $phone]);
        $user = (new UserModel())->findById($user['id']);
        Auth::login($user);
        header('Location: ' . APP_URL . '/?route=profile');
    }
}
