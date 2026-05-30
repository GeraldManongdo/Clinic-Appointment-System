<?php
class AppointmentController extends Controller
{
    public function index(): void
    {
        $serviceModel = new ServiceModel();
        $blockedModel = new BlockedDateModel();
        $settings = new SiteSettingModel();
        $appointmentModel = new AppointmentModel();

        $blockedDates = $blockedModel->blockedDates();
        $bookedDates = $appointmentModel->bookedDates(14);
        $calendarDates = [];
        for ($i = 0; $i < 14; $i++) {
            $date = date('Y-m-d', strtotime('+' . $i . ' days'));
            $status = 'available';
            if (in_array($date, $blockedDates, true)) {
                $status = 'blocked';
            } elseif (in_array($date, $bookedDates, true)) {
                $status = 'booked';
            }
            $calendarDates[] = ['date' => $date, 'label' => date('D, M j', strtotime($date)), 'status' => $status];
        }

        $data = [
            'services' => $serviceModel->visible(),
            'blockedDates' => $blockedDates,
            'site' => $settings->all(),
            'user' => Auth::user(),
            'calendarDates' => $calendarDates,
        ];
        $this->render('appointment', $data);
    }

    public function book(): void
    {
        if (!Auth::check()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Please log in before booking.']);
            return;
        }

        $user = Auth::user();
        $serviceId = (int)($_POST['service_id'] ?? 0);
        $date = $_POST['appointment_date'] ?? '';
        $time = $_POST['appointment_time'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';
        $paymentReference = trim($_POST['payment_reference'] ?? '');

        if (!$serviceId || !$date || !$time) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Please complete the booking form.']);
            return;
        }

        $appointmentModel = new AppointmentModel();
        $blockedModel = new BlockedDateModel();

        if (in_array($date, $blockedModel->blockedDates(), true)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'This date is blocked and cannot be booked.']);
            return;
        }

        if ($appointmentModel->existsDate($date)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'This date already has an appointment. Choose another date.']);
            return;
        }

        if ($appointmentModel->existsSlot($date, $time)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'This time slot is already booked.']);
            return;
        }

        $receiptPath = '';
        if (!empty($_FILES['receipt']['tmp_name'])) {
            $upload = $_FILES['receipt'];
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $upload['name']);
            $destination = UPLOAD_PATH . '/receipts/' . $filename;
            move_uploaded_file($upload['tmp_name'], $destination);
            $receiptPath = 'receipts/' . $filename;
        }

        $appointmentModel->create([
            'user_id' => $user['id'],
            'service_id' => $serviceId,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'receipt_path' => $receiptPath,
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Booking successfully created.']);
    }
}
