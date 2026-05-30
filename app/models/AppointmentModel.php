<?php
class AppointmentModel extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time, payment_method, payment_reference, receipt_path, status, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$data['user_id'], $data['service_id'], $data['appointment_date'], $data['appointment_time'], $data['payment_method'], $data['payment_reference'], $data['receipt_path'], 'pending', $data['notes']]);
        return (int)$this->db->lastInsertId();
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT a.*, s.title AS service_title FROM appointments a LEFT JOIN services s ON a.service_id = s.id WHERE a.user_id = ? ORDER BY a.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findPending(): array
    {
        $stmt = $this->db->query('SELECT a.*, u.name AS user_name, u.email AS user_email, s.title AS service_title FROM appointments a JOIN users u ON a.user_id = u.id LEFT JOIN services s ON a.service_id = s.id WHERE a.status = "pending" ORDER BY a.created_at DESC LIMIT 20');
        return $stmt->fetchAll();
    }

    public function allAdmin(string $search = ''): array
    {
        $query = 'SELECT a.*, u.name AS user_name, u.email AS user_email, s.title AS service_title FROM appointments a JOIN users u ON a.user_id = u.id LEFT JOIN services s ON a.service_id = s.id';
        if ($search) {
            $query .= ' WHERE u.name LIKE :search OR u.email LIKE :search OR s.title LIKE :search OR a.status LIKE :search';
        }
        $query .= ' ORDER BY a.created_at DESC LIMIT 100';
        $stmt = $this->db->prepare($query);
        if ($search) {
            $stmt->execute(['search' => '%' . $search . '%']);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status, string $notes = null): bool
    {
        $stmt = $this->db->prepare('UPDATE appointments SET status = ?, admin_notes = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$status, $notes, $id]);
    }

    public function cancel(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE appointments SET status = "cancelled", updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function existsSlot(string $date, string $time): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status IN ("pending","confirmed")');
        $stmt->execute([$date, $time]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function existsDate(string $date): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND status IN ("pending","confirmed")');
        $stmt->execute([$date]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function bookedSlots(string $date): array
    {
        $stmt = $this->db->prepare('SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status IN ("pending","confirmed")');
        $stmt->execute([$date]);
        return array_column($stmt->fetchAll(), 'appointment_time');
    }

    public function bookedDates(int $limit = 14): array
    {
        $stmt = $this->db->prepare('SELECT DISTINCT appointment_date FROM appointments WHERE appointment_date >= CURDATE() AND status IN ("pending","confirmed") ORDER BY appointment_date ASC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(), 'appointment_date');
    }
}
