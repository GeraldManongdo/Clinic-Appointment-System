<?php
class ContactController extends Controller
{
    public function send(): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if (!$data['name'] || !$data['email'] || !$data['message']) {
            header('Location: ' . APP_URL . '?route=home&msg=contact_error');
            return;
        }

        (new ContactMessageModel())->create($data);
        header('Location: ' . APP_URL . '?route=home&msg=contact_ok');
    }
}
