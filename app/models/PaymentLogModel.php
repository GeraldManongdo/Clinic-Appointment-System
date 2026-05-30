<?php
class PaymentLogModel extends Model
{
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO payment_logs (appointment_id, method, reference, status, created_at) VALUES (?, ?, ?, ?, NOW())');
        return $stmt->execute([$data['appointment_id'], $data['method'], $data['reference'], $data['status']]);
    }
}
