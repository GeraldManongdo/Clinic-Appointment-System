<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AjaxController extends Controller
{
    public function handle(): void
    {
        $action = $_REQUEST['action'] ?? '';
        header('Content-Type: application/json');
        switch ($action) {
            case 'send_otp':
                $this->sendOtp();
                break;
            case 'load_services':
                $this->loadServices();
                break;
            case 'blocked_dates':
                $this->blockedDates();
                break;
            case 'blocked_slots':
                $this->blockedSlots();
                break;
            case 'cancel_appointment':
                $this->cancelAppointment();
                break;
            default:
                echo json_encode(['success' => false]);
                break;
        }
    }

    private function sendOtp(): void
    {
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Valid email required.']);
            return;
        }
        $code = rand(100000, 999999);
        (new EmailVerificationModel())->create(['email' => $email, 'code' => $code, 'expires_at' => date('Y-m-d H:i:s', strtotime('+15 minutes'))]);
        $body = "<p>Your clinic booking OTP is <strong>{$code}</strong>. Enter it within 15 minutes.</p>";
        $sent = $this->sendMail($email, 'Booking OTP Verification', $body);
        echo json_encode(['success' => $sent, 'message' => $sent ? 'OTP sent to your email.' : 'Unable to send OTP.']);
    }

    private function loadServices(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 4;
        $offset = ($page - 1) * $limit;
        $services = (new ServiceModel())->all($limit, $offset);
        echo json_encode(['success' => true, 'services' => $services]);
    }

    private function blockedDates(): void
    {
        echo json_encode(['success' => true, 'dates' => (new BlockedDateModel())->blockedDates()]);
    }

    private function blockedSlots(): void
    {
        $date = $_GET['date'] ?? '';
        if (!$date) {
            echo json_encode(['success' => false, 'slots' => []]);
            return;
        }

        $blockedModel = new BlockedDateModel();
        $appointmentModel = new AppointmentModel();
        $allSlots = ["09:00", "10:00", "11:00", "13:00", "14:00", "15:00"];

        if (in_array($date, $blockedModel->blockedDates(), true) || $appointmentModel->existsDate($date)) {
            echo json_encode(['success' => true, 'slots' => $allSlots]);
            return;
        }

        $blocked = $blockedModel->blockedSlots($date);
        $booked = $appointmentModel->bookedSlots($date);
        $slots = array_unique(array_merge($blocked, $booked));
        echo json_encode(['success' => true, 'slots' => $slots]);
    }

    private function cancelAppointment(): void
    {
        if (!Auth::check()) {
            echo json_encode(['success' => false]);
            return;
        }
        $id = (int)($_POST['id'] ?? 0);
        $result = (new AppointmentModel())->cancel($id);
        echo json_encode(['success' => $result]);
    }

    private function sendMail(string $to, string $subject, string $body): bool
    {
        require_once __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
        require_once __DIR__ . '/../../PHPMailer-master/src/SMTP.php';
        require_once __DIR__ . '/../../PHPMailer-master/src/Exception.php';
        $config = require __DIR__ . '/../../config/mail.php';
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = $config['encryption'];
            $mail->Port = $config['port'];
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
