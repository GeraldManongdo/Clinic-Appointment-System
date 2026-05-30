<?php
class AdminController extends Controller
{
    protected function render(string $view, array $data = [], string $layout = 'layouts/admin'): void
    {
        parent::render($view, $data, $layout);
    }

    public function dashboard(): void
    {
        $appointmentModel = new AppointmentModel();
        $serviceModel = new ServiceModel();
        $settings = new SiteSettingModel();
        $stats = [
            'totalAppointments' => count($appointmentModel->allAdmin()),
            'pendingAppointments' => count($appointmentModel->findPending()),
            'services' => count($serviceModel->adminList()),
            'siteSettings' => $settings->all(),
        ];
        $this->render('admin/dashboard', ['stats' => $stats, 'recent' => $appointmentModel->findPending(), 'site' => $stats['siteSettings']]);
    }

    public function appointments(): void
    {
        $search = trim($_GET['search'] ?? '');
        $appointments = (new AppointmentModel())->allAdmin($search);
        $this->render('admin/appointments', ['appointments' => $appointments, 'site' => (new SiteSettingModel())->all(), 'search' => $search]);
    }

    public function services(): void
    {
        $services = (new ServiceModel())->adminList();
        $this->render('admin/services', ['services' => $services, 'site' => (new SiteSettingModel())->all()]);
    }

    public function schedule(): void
    {
        $blocked = (new BlockedDateModel())->all();
        $this->render('admin/schedule', ['blocked' => $blocked, 'site' => (new SiteSettingModel())->all()]);
    }

    public function content(): void
    {
        $this->render('admin/content', ['site' => (new SiteSettingModel())->all()]);
    }

    public function messages(): void
    {
        $messages = (new ContactMessageModel())->latest(50);
        $this->render('admin/messages', ['messages' => $messages, 'site' => (new SiteSettingModel())->all()]);
    }

    public function settings(): void
    {
        $this->render('admin/settings', ['site' => (new SiteSettingModel())->all()]);
    }

    public function accounts(): void
    {
        $users = (new UserModel())->allUsers();
        $this->render('admin/accounts', ['users' => $users, 'site' => (new SiteSettingModel())->all()]);
    }
}
